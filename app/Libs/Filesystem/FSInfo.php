<?php

namespace App\Libs\Filesystem;


/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\Filesystem;
 */
class FSInfo
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var string
     */
    private $fullPath;

    /**
     * @var string
     */
    private $dirname;

    /**
     * @var string
     */
    private $basename;

    /**
     * @var string
     */
    private $ext;

    /**
     * @var string
     */
    private $filenameWithoutExt;

    /**
     * FS Info Constructor
     *
     * @param string $filename
     * @param string $rootPath
     */
    public function __construct(string $filename, string $rootPath = '/')
    {
        $this->filename = FSUtils::checkRedundantDirSeparators($filename);
        $this->rootPath = FSUtils::checkRedundantDirSeparators($rootPath . DIRECTORY_SEPARATOR);
        $this->fullPath = FSUtils::checkRedundantDirSeparators($this->rootPath . $filename);

        $this->processFile();
    }

    /**
     * Process file information
     */
    private function processFile()
    {
        $pathInfo = pathinfo($this->fullPath);

        if (isset($pathInfo['dirname'])) {
            $this->dirname = $pathInfo['dirname'];
        }
        if (isset($pathInfo['basename'])) {
            $this->basename = $pathInfo['basename'];
        }
        if (isset($pathInfo['extension'])) {
            $this->ext = $pathInfo['extension'];
        } else {
            $this->ext = '';
        }
        if (isset($pathInfo['filename'])) {
            $this->filenameWithoutExt = $pathInfo['filename'];
        }
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return $this->dirname;
    }

    /**
     * @return string
     */
    public function getBasename(): string
    {
        return $this->basename;
    }

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getFilenameWithoutExt(): string
    {
        return $this->filenameWithoutExt;
    }
}
