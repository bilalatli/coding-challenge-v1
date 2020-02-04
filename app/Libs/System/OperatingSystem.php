<?php

namespace App\Libs\System;

use App\Libs\Constants\OperatingSystem AS OSDefinitions;
use App\Libs\Performance\MemoryCache\MemoryCache;

/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\System;
 */
class OperatingSystem
{
    use MemoryCache;

    /**
     * Get Operating System
     *
     * @return string
     */
    public static function getOS()
    {
        // Info : Memory cache increases performance by about %8 ~ 15%

        $cacheKey = self::cacheKey('OS:' . __METHOD__);

        return self::cacheGet($cacheKey, function () {
            /**
             * php_uname method available on Php 4.0.2 or higher
             */
            $info = strtolower(php_uname('s'));

            if (strpos($info, 'win') !== false) {
                return OSDefinitions::WINDOWS;
            } else if (strpos($info, 'linux') !== false) {
                return OSDefinitions::LINUX;
            } else if (strpos($info, 'mac') !== false) {
                return OSDefinitions::MACOS;
            } else {
                // Info: Unknown operating systems
                return OSDefinitions::UNKNOWN;
            }
        });
    }
}
