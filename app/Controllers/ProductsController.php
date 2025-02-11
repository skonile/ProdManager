<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Models\Products;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Database;

class ProductsController extends BaseController{
    private ProductModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new ProductModel(Database::getInstance());
    }

    public function getProducts(Request $request, Response $response){
        $route = $request->getUri();
        $pageNum = 1;
        $limit = SOFTWARE_ITEM_LIMIT;
        $products = [];

        if($request->getSecondUriPart() !== null){
            $pageNum = $this->getPageOrLimitNum($request->getSecondUriPart());
            $pageNum = ($pageNum == false)? 1: $pageNum;
        }

        if($request->getThirdUriPart() !== null){
            $limit = $this->getPageOrLimitNum($request->getThirdUriPart());
            $limit = ($limit == false)? SOFTWARE_ITEM_LIMIT: $limit;
        }

        if(count($_GET) == 0)
            $products = $this->getUnfilteredProducts($pageNum, $limit);
        else
            $products = $this->getFilteredProducts($request, $pageNum, $limit);

        $this->render(
            'products/products', 
            [
                'route' => $route,
                'products' => $products,
                'categories' => (new CategoryModel(Database::getInstance()))->getAllCategories(),
                'pageNum' => $pageNum, 
                'limit' => $limit,
            ]
        );
    }

    protected function getUnfilteredProducts(int $pageNum, int $limit): Products{
        return $this->model->getAllProducts($pageNum, $limit);
    }

    protected function getFilteredProducts(Request $request, int $pageNum, int $limit): Products{
        $code        = $this->getProductsFilterString($request, 'product-code');
        $brands      = $this->getProductsFilterIds($request, 'brands');
        $categories  = $this->getProductsFilterIds($request, 'product-categories');
        $tags        = $this->getProductsFilterIds($request, 'tags');
        $isInstock   = $this->getProductsFilterBool($request, 'isinstock');
        $isPublished = $this->getProductsFilterBool($request, 'ispublished');

        return $this->model->getFilteredProducts(
            code: $code,
            brands: $brands,
            categories: $categories,
            tags: $tags,
            isInstock: $isInstock,
            isPublished: $isPublished,
            pageNum: $pageNum,
            limit: $limit
        );
    }

    protected function getProductsFilterString(Request $request, string $key): string|null{
        if(\array_key_exists($key, $request->getGET()) && $request->get($key) != "")
            return $request->get($key);
        return null;
    }

    protected function getProductsFilterIds(Request $request, string $key): array|null{
        if(\array_key_exists($key, $request->getGET()) && $request->get($key) != "")
            return $this->getIdsFromStr($request->get($key));
        return null;
    }

    protected function getProductsFilterBool(Request $request, string $key): bool|null{
        $value = null;
        if(\array_key_exists($key, $request->getGET()) && $request->get($key) != ""){
            $strValue = \trim(\strtolower($request->get($key)));
            if($strValue == "true")
                $value = true;
            if($strValue == "false")
                $value = false;
            return $value;
        }
        return $value;
    }

    /**
     * Takes a string of comma-separated string and 
     * tries to get integers that are not less than one.
     * 
     * @param string $str The comma-separeted string
     * @return array of all the given ids that are not less than 1
     */
    protected function getIdsFromStr(string $str): array{
        $ids = [];
        $strIds = \explode(',', $str);

        foreach($strIds as $strId){
            $strId = \trim($strId);
            try{
                $tempId = (int) $strId;
                if($tempId == 0) continue;
                $ids[] = $tempId;
            } catch(\Error){
                continue;
            }
        }
        return $ids;
    }
}