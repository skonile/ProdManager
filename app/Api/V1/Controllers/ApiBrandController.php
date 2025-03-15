<?php
declare(strict_types = 1);

namespace App\Api\V1\Controllers;

use App\Api\V1\Controllers\ApiBaseController;
use App\Models\BrandModel;
use App\Database;
use App\Request;
use App\Response;
use App\Models\Brand;

class ApiBrandController extends ApiBaseController{
    protected BrandModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new BrandModel(Database::getInstance());
    }

    public function getBrands(Request $request, Response $response){
        $brands = $this->model->getAllBrands();
        $brands = array_map([$this, 'brandToArray'], $brands);
        $response->json(['result' => $brands]);
        $response->send();
    }

    public function addBrand(Request $request, Response $response){
        $brandName = $this->getBrandName($request);
        $brand = $this->model->addBrand($brandName);
        if($brand)
            $response->json(['result' => $this->brandToArray($brand)], 201);
        else
            $response->json(['error' => 'Brand not added'], 500);
        $response->send();
    }

    public function getBrand(Request $request, Response $response){
        $brandId = (int) $request->getUriVariable('id');
        $brand = $this->model->getBrand($brandId);
        if($brand)
            $response->json(['result' => $this->brandToArray($brand)]);
        else
            $response->json(['error' => 'Brand not found'], 404);
        $response->send();
    }

    public function updateBrand(Request $request, Response $response){
        $brandId = (int) $request->getUriVariable('id');
        $brandName = $this->getBrandName($request);
        $brand = $this->model->updateBrand($brandId, $brandName);
        if($brand)
            $response->json(['result' => $this->brandToArray(new Brand($brandId, $brandName))]);
        else
            $response->json(['error' => 'Brand not updated'], 500);
        $response->send();
    }

    public function deleteBrand(Request $request, Response $response){
        $brandId = (int) $request->getUriVariable('id');
        $brand = $this->model->deleteBrand($brandId);
        if($brand)
            $response->json(['result' => 'Brand deleted']);
        else
            $response->json(['error' => 'Brand not deleted'], 500);
        $response->send();
    }

    protected function getBrandName(Request $request): string{
        $data = json_decode($request->getBody(), true);
        return $data['brand_name'];
    }

    protected function brandToArray($brand){
        return [
            'brand_id' => $brand->getId(),
            'brand_name' => $brand->getName(),
        ];
    }
}