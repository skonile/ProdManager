<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Database;
use App\Request;
use App\Response;
use App\Models\BrandModel;

use App\Exceptions\AppException;
use App\Exceptions\NotImplementedException;
use App\Exceptions\ControllerExceptions\InvalidArgException;
use App\Exceptions\ControllerExceptions\InvalidURLException;
use App\Session;

class BrandsController extends BaseController{
    private BrandModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new BrandModel(Database::getInstance());
    }

    public function getBrands(Request $request, Response $response){
        $page = 1;
        $limit = 10;

        try{
            if($request->getSecondUriPart() !== null)
                $page = $this->getBrandsArgNum($request->getSecondUriPart());
            
            if($request->getThirdUriPart() !== null)
                $limit = $this->getBrandsArgNum($request->getThirdUriPart());
        } catch(AppException){
            throw new InvalidURLException();
        }

        $brands = $this->model->getBrands($page, $limit);

        $this->render('brands/brands', [
            'route' => $request->getFirstUriPart(),
            'brands' => $brands
        ]);
    }

    public function getBrand(Request $request, Response $response){
        $brandId = $request->getSecondUriPart();
        $brand = false;
        try{
            $brandId = $this->getBrandsArgNum($brandId);
            $brand = $this->model->getBrand($brandId);
        } catch(\Throwable $e){
        }
        
        if($brand === false){
            $message = "Brand does not exist.";
            Session::getInstance()->setMessage($message);
            $response->sendToPage("/brands");
        }

        $this->render('brands/brand', [
            'brand' => $brand
        ]);
    }

    public function getCreateBrand(Request $request, Response $response){
        throw new NotImplementedException();
    }

    public function createBrand(Request $request, Response $response){
        throw new NotImplementedException();
    }

    public function updateBrand(Request $request, Response $response){
        throw new NotImplementedException();
    }

    public function deleteBrand(Request $request, Response $response){
        throw new NotImplementedException();
    }

    private function getBrandsArgNum(string $argNum): int{
        $argNum = (int) $argNum;
        if($argNum < 1)
            throw new InvalidArgException();
        return $argNum;
    }
}