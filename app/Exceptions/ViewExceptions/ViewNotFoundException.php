<?php
declare(strict_types = 1);

namespace App\Exceptions\ViewExceptions;

use App\Exceptions\AppException;

class ViewNotFoundException extends AppException{
    protected $message = "View Not Found";
}