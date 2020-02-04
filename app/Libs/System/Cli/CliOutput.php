<?php

namespace App\Libs\System\Cli;


/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\System\Cli;
 */
class CliOutput
{
    /**
     * STDOut
     *
     * @param string $message
     * @param string $prefix
     */
    public static function stdOut(string $message, string $prefix = '> INFO :')
    {
        self::cliOut(STDOUT, $message, $prefix);
    }

    /**
     * STDErr
     *
     * @param string $message
     * @param string $prefix
     */
    public static function strErr(string $message, string $prefix = '> ERROR :')
    {
        self::cliOut(STDERR, $message, $prefix);
    }

    /**
     * Write Console Output
     *
     * @param resource $type
     * @param string $message
     * @param string $prefix
     */
    private static function cliOut($type, string $message, string $prefix = '>')
    {
        fwrite($type,$prefix.' '.$message);
    }
}
