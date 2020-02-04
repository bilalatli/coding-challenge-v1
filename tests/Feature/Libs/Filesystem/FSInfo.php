<?php

namespace Tests\Feature\Libs\Filesystem;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FSInfo extends TestCase
{
    /**
     * Test Basename
     *
     * @return void
     */
    public function testBasename()
    {
        $file = new \App\Libs\Filesystem\FSInfo('index.php', public_path());

        $this->assertEquals('index.php', $file->getBasename(), 'Basename is wrong...');
    }

    /**
     * Test Extension
     *
     * @return void
     */
    public function testExtension()
    {
        $file = new \App\Libs\Filesystem\FSInfo('index.php', public_path());

        $this->assertEquals('php', $file->getExt(), 'Extension is wrong...');
    }

    /**
     * Test Filename Without Extension
     *
     * @return void
     */
    public function testFilenameWithoutExt()
    {
        $file = new \App\Libs\Filesystem\FSInfo('index.php', public_path());

        $this->assertEquals('index', $file->getFilenameWithoutExt(), 'Filename is wrong...');
    }

    /**
     * Test Directory Name
     *
     * @return void
     */
    public function testDirectoryName()
    {
        $file = new \App\Libs\Filesystem\FSInfo('index.php', public_path());

        $this->assertEquals(dirname(public_path('index.php')), $file->getDirname(), 'Directory name is wrong...');
    }

    /**
     * Test Full File Path
     *
     * @return void
     */
    public function testFullPath()
    {
        $file = new \App\Libs\Filesystem\FSInfo('index.php', public_path());

        $this->assertEquals(public_path('index.php'), $file->getFullPath(), 'Full file path is wrong...');
    }
}
