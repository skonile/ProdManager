<?php
declare(strict_types = 1);

namespace App\Api\V1\Controllers;

use App\Controllers\BaseController;
use App\Request;
use App\Response;

class ApiBaseController extends BaseController{
    public function __construct(){
        parent::__construct();
    }
}