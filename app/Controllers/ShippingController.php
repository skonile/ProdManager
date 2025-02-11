<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Database;
use App\Models\ShippingModel;
use App\Exceptions\NotImplementedException;

class ShippingController extends BaseController{
    private ShippingModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new ShippingModel(Database::getInstance());
    }

    public function getShipping(Request $request, Response $response){
        throw new NotImplementedException();
    }
}