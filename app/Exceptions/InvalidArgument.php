<?php

namespace App\Exceptions;

use Exception;

class InvalidArgument extends Exception
{
    protected $message = 'Invalid Argument.';
    protected $code = 404;

}

