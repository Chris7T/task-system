<?php

namespace App\Exceptions;

use Exception;

class ProjectNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Project not found');
    }
}

