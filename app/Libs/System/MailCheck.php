<?php

namespace App\Libs\System;


/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\System;
 */
class MailCheck
{
    /**
     * Check Mail Environments
     *
     * Return `false` if variables not set
     *
     * @return bool
     */
    public static function checkEnvironments()
    {
        if (
            env('MAIL_USERNAME') === null ||
            env('MAIL_PASSWORD') ||
            env('MAIL_FROM_ADDRESS')
        ) {
            return false;
        }
        return true;
    }
}