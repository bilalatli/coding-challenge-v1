<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class PatternDoesNotMatch
 *
 * @package App\Exceptions
 */
class PatternDoesNotMatch extends Exception
{
    /**
     * PatternDoesNotMatch constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Pattern does not match", $code = 0xF256AB, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
