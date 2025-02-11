<?php
declare(strict_types = 1);

namespace App\Exceptions;

use App\Exceptions\AppException;

class NotImplementedException extends AppException{
    protected $message = "Class/Method not implemented";
}