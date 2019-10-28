<?php

namespace App\Exceptions;

use Exception;

class InvalidDateFormat extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::debug('Invalid Date Format.');
    }
}
