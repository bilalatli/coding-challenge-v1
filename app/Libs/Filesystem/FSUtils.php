<?php

namespace App\Libs\Filesystem;

use App\Libs\System\OperatingSystem;

/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\Filesystem;
 */
class FSUtils
{
    /**
     * Check duplicated directory separators & replace it
     *
     * Note : DIRECTORY_SEPARATORS only available > Php 5.6.*
     *
     * @param string $filename
     * @param string $separator
     *
     * @return string|string[]|null
     */
    public static function checkRedundantDirSeparators(string $filename, string $separator = DIRECTORY_SEPARATOR)
    {
        if (OperatingSystem::getOS() === \App\Libs\Constants\OperatingSystem::WINDOWS) {
            // Windows using \ char for separate directory. Need to be escape
            $escapeChar = '\\';
        } else {
            // Other known operating systems using / char for separate directory
            $escapeChar = '';
        }
        return preg_replace('#' . $escapeChar . $separator . '+#', "$separator", $filename);
    }
}
