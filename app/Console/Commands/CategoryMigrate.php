<?php

namespace App\Console\Commands;

use App\Categories;
use App\Exceptions\PatternDoesNotMatch;
use App\Libs\Dynamics\Category;
use App\Libs\Filesystem\FSInfo;
use App\Mail\CategoryMigrationReportMail;
use App\Imports\CategoriesImport;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CategoryMigrate extends Command
{
    /**
     * Allowed File Extensions
     */
    const ALLOW_EXTENSIONS = [ 'xls', 'xlsx' ];

    /**
     * Cache Import Key
     */
    const CACHE_IMPORT_KEY = 'Category:Last:Import';

    /**
     * Migration File
     *
     * @var null|FSInfo
     */
    private $migFile = null;

    /**
     * Migration File TS
     *
     * @var int
     */
    private $migFileTS = 0;

    /**
     * Migration Files List
     *
     * @var array
     */
    private $migFiles = [];

    /**
     * @var Carbon
     */
    private $fileDate;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate remote categories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastImport = Cache::get(self::CACHE_IMPORT_KEY, '-');

        if ($lastImport === '-') {
            $this->warn('Last Migrated File : none');
        } else {
            $this->info('Last Migrated File : ' . $lastImport);
        }

        $this->separateLines();

        $this->info('Fetching file list from remote server...');
        $files = Storage::disk('90pixel')->files('categories');
        $totalFiles = count($files);

        $this->info('# Found ' . $totalFiles . ' migration file');

        foreach ($files as $file) {
            $file = ( new FSInfo($file, '/') );
            if (!$this->isAllowedExtension($file->getExt())) {
                $this->comment("  > DENY  : " . $file->getBasename() . " - Extension not allowed. Passing next file");
                continue;
            }
            try {
                $fileTime = $this->parsePattern($file->getFilenameWithoutExt());
            } catch (PatternDoesNotMatch $e) {
                $this->comment("  > DENY  : " . $file->getBasename() . " - Pattern doesn't match");
                continue;
            }

            $this->info('  > ALLOW : ' . $file->getBasename());
            $this->migFiles[$fileTime] = $file;
        }

        $totalAllowedFiles = count($this->migFiles);
        $this->info('# Total File : ' . $totalFiles . ', Allowed Migration Files : ' . $totalAllowedFiles . ', Disallowed Files : ' . ( $totalFiles - $totalAllowedFiles ));

        $this->separateLines();

        //region Select Newer Migration File
        foreach ($this->migFiles as $date => $file) {
            if (( $date > $this->migFileTS ) || ( $lastImport !== '-' && $date > $lastImport )) {
                $this->migFile = $file;
                $this->migFileTS = $date;
            }
        }
        //endregion

        if ($this->migFile === null) {
            $this->info("New migration file not found.");
            return;
        }

        $this->fileDate = Carbon::createFromFormat('YmdHis', $this->migFileTS);

        $this->info("Selected migration file : " . $this->migFile->getBasename());
        $this->info("File Date : " . $this->fileDate->toDateTimeString());

        //region Download Remote Migration File into Local Filesystem
        try {
            $file = Storage::disk('90pixel')->get('categories/' . $this->migFile->getBasename());
        } catch (FileNotFoundException $e) {
            $this->error('# ERROR : File deleted on runtime. Please check connection or remote file system');
            return;
        }

        if (!Storage::disk('migrations')->put($this->migFile->getBasename(), $file)) {
            $this->error('# ERROR : File download error. Please check permissions');
            return;
        }
        //endregion

        $this->info("  > File Download : " . storage_path('migrations' . DIRECTORY_SEPARATOR . $this->migFile->getBasename()));

        $this->separateLines();

        // Apply Migration
        $this->applyMigration();
    }

    private function applyMigration()
    {
        $this->info('# Starting to migrations from downloaded file');

        try {
            $migrationData = Excel::toArray(new CategoriesImport, $this->migFile->getBasename(), 'migrations');
            $migrationData = $migrationData[0];
            // Remove Caption Row
            unset($migrationData[0]);

            foreach ($migrationData as $key => $migrationDataItem) {
                // It removes null or blank array items
                $migrationData[$key] = array_filter($migrationDataItem);
            }
        } catch (Exception $e) {
            $this->error('# ERROR : An error occurred on migration file read action. Please check your file reader plugin');
            return;
        }

        // Truncate all table data
        /** @noinspection PhpUndefinedMethodInspection */
        Categories::truncate();

        $this->info("Migration file has : " . count($migrationData) . " category");

        /**
         * @var Category[] $categories
         */
        $categories = [];
        foreach ($migrationData as $item) {
            Category::addCategory($categories, $item, null);
        }

        $this->info("Importing category records...");

        foreach ($categories as $category) {
            $categoryItem = new Categories();
            $categoryItem->id = $category->id;
            $categoryItem->category = $category->name;
            $categoryItem->parent_id = $category->parentCategoryId;
            if ($categoryItem->save()) {
                $this->info("SUCCESS : Imported '" . $category->name . "' '#" . $category->id . "'");
            } else {
                $this->error("ERROR : An error occurred on category import");
                return;
            }
        }

        Cache::put(self::CACHE_IMPORT_KEY, $this->migFileTS, now()->addYears(500));
        $this->info('Category migration completed.');

        //region Prepare mailable contents

        // Info: Mailable class has `Queueable` bus. This will affect the processing when the QUEUE_DRIVER in the environment is changed.
        $mailable = new CategoryMigrationReportMail([
            'last_import_key' => $this->migFileTS,
            'total_count'     => count($categories),
        ]);

        Mail::to(env('NOTIFICATION_MAIL'))->send($mailable);
        //endregion
    }


    /**
     * Parse file name with migration file pattern
     *
     * @param string $fileName
     *
     * @return string
     *
     * @throws PatternDoesNotMatch
     */
    private function parsePattern(string $fileName)
    {
        preg_match('/\w+\-([0-9]+)/i', $fileName, $matches);

        // Pattern check
        if (count($matches) != 2 || !is_numeric($matches[1])) {
            throw new PatternDoesNotMatch("$fileName - Pattern doesn't match");
        }
        return $matches[1];
    }

    /**
     * Check extension is allowed or disallowed ?
     *
     * @param string $ext
     *
     * @return bool
     */
    private function isAllowedExtension(string $ext)
    {
        return ( in_array($ext, self::ALLOW_EXTENSIONS) );
    }

    /**
     * Write separator line console output
     */
    private function separateLines()
    {
        $this->comment(' ');
        $this->comment('-------------------------------------------------------------------------------------------------------');
        $this->comment(' ');
    }
}
