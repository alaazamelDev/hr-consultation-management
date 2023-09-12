<?php

namespace App\Exceptions;

use Exception;

class DuplicatedEntryException extends Exception
{
    protected $message = 'Duplicated Entry.';
    protected $code = 400;

}
