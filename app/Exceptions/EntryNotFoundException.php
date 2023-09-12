<?php

namespace App\Exceptions;

use Exception;

class EntryNotFoundException extends Exception
{
    protected $message = 'Entry not found.';
    protected $code = 404;

}
