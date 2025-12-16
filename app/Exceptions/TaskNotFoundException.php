<?php

namespace App\Exceptions;

use Exception;

class TaskNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Task not found');
    }
}

