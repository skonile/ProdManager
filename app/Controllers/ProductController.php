<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Database;
use App\Models\Product;
use App\Models\ProductPlugin;
use App\Models\Shipping;
use App\Models\TagModel;
use App\Models\Condition;
use App\Models\BrandModel;
use App\Models\ProductModel;

use App\Models\CategoryModel;
use App\Exceptions\NotImplementedException;
use App\Exceptions\ViewExceptions\ViewNotFoundException;
use App\Plugins\PluginManager;
use App\Session;

class ProductController extends BaseController{
    private ProductModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new ProductModel(Database::getInstance());
    }

    public function getProduct(Request $request, Response $response){
        $pluginsModel = new ProductPlugin(Database::getInstance());

        if($request->getSecondUriPart() !== null){
            $id = $this->getPageOrLimitNum($request->getSecondUriPart());
            if($id === false) 
                throw new ViewNotFoundException();

            $product = $this->model->getProduct($id);
        }

        $productPlugins = $pluginsModel->getPluginNamesByProductId($product->getId());

        $this->renderProduct($request->getFirstUriPart(), $product, $productPlugins);
    }

    public function getCreateProduct(Request $request, Response $response){
        $this->renderProduct($request->getUri());
    }

    /**
     * Add a new product to the database and handle the product media files(images).
     * 
     * @param Request $request
     * @param Response $response
     */
    public function createProduct(Request $request, Response $response){
        $brandModel    = new BrandModel(Database::getInstance());
        $brand         = null;
        $name          = $_POST['product-name'];
        $code          = $_POST['product-code'];
        $price         = (float) $_POST['product-price'];
        $quantity      = (int) $_POST['product-quantity'];
        $description   = $_POST['product-description'];
        $condition     = Condition::fromString($_POST['product-condition']) ?? Condition::New;
        $shippingLocal = $_POST['product-shipping-local'];
        $shippingNationwide = $_POST['product-shipping-nationwide'];
        $shippingInternational = $_POST['product-shipping-international'];
        $isPublished   = (bool)((int) $_POST['product-status']);
        $categories    = $this->getCategoriesFromIds($_POST['product-category'] ?? []);
        $tags          = $this->getTagsFromIds($_POST['product-tag'] ?? []);
        $prodBrand     = (int) $_POST['product-brand'] ?? null;
        $productClientId = $_POST['product-client-id'] ?? '';
        $images        = [];
        $plugins       = $_POST['plugins'] ?? [];

        $shippingLocal = ($shippingLocal == '')? null: (float) $shippingLocal;
        $shippingNationwide = ($shippingNationwide == '')? null: (float) $shippingNationwide;
        $shippingInternational = ($shippingInternational == '')? null: (float) $shippingInternational;

        $tmpImgs = $this->productsImagesUsingClientId($productClientId);
        foreach($tmpImgs as $img){
            $images[] = basename($img);
        }

        // Get the Brand object from brand id.
        if($prodBrand != null && $prodBrand != '')
            $brand = $brandModel->getBrand($prodBrand);

        $shipping   = new Shipping($shippingLocal, $shippingNationwide, $shippingInternational);
        $newProduct = new Product(null, $name, $code, $description, 
            $price, $brand, $categories, $tags, $isPublished, 
            $images, $quantity, $shipping, $condition);

        $id = $this->model->addProduct($newProduct);
        $this->moveProductImages($tmpImgs);

        $prodPlugin = new ProductPlugin(Database::getInstance());
        foreach($plugins as $plugin){
            $prodPlugin->addPluginNameToProduct($id, $plugin);
        }

        // Redirect to the added product.
        $response->sendToPage('/product/' . $id);
    }

    /**
     * Get the current product's images from the temporary uploaded file direcory.
     * 
     * Use the client product's id to determine the images as it is prepended to the images names.
     *
     * @param string $clientId The client id created by the browser
     * @return array The product's images with full path
     */
    private function productsImagesUsingClientId(string $clientId): array{
        $dirsFiles = scandir(TMP_UPLOADED_FILES);
        $imgs = [];
        foreach($dirsFiles as $dirFile){
            if(is_file(TMP_UPLOADED_FILES . $dirFile)){
                $fileClientId = explode('-', $dirFile)[0];
                if($fileClientId == $clientId) $imgs[] = TMP_UPLOADED_FILES . $dirFile;
            }
        }
        return $imgs;
    }

    /**
     * Move the product's images to the a permanent directory.
     *
     * @param array $prodImages The images to be moved
     * @return void
     */
    private function moveProductImages(array $prodImages){
        foreach($prodImages as $image){
            if(file_exists($image)){
                rename($image, PRODUCT_IMAGES_PATH . basename($image));
            }
        }
    }

    private function getCategoriesFromIds(array $catIds): array{
        if(count($catIds) == 0) return [];

        $catModel   = new CategoryModel(Database::getInstance());
        $categories = [];

        foreach($catIds as $catId){
            $category = $catModel->getCategory((int) $catId);
            if($category !== false)
                $categories[] = $category;
        }

        return $categories;
    }

    private function getTagsFromIds(array $tagIds): array{
        if(count($tagIds) == 0) return [];

        $tagModel   = new TagModel(Database::getInstance());
        $tags = [];

        foreach($tagIds as $tagId){
            $tag = $tagModel->getTag((int) $tagId);
            if($tag !== false)
                $tags[] = $tag;
        }

        return $tags;
    }

    public function updateProduct(Request $request, Response $response){
        $brandModel    = new BrandModel(Database::getInstance());
        $brand         = null;
        $id            = (int) $_POST['product-id'];
        $name          = $_POST['product-name'];
        $code          = $_POST['product-code'];
        $price         = (float) $_POST['product-price'];
        $quantity      = (int) $_POST['product-quantity'];
        $description   = $_POST['product-description'];
        $condition     = Condition::fromString($_POST['product-condition']) ?? Condition::New;
        $shippingLocal = $_POST['product-shipping-local'];
        $shippingNationwide = $_POST['product-shipping-nationwide'];
        $shippingInternational = $_POST['product-shipping-international'];
        $isPublished   = (bool)((int) $_POST['product-status']);
        $categories    = $this->getCategoriesFromIds($_POST['product-category'] ?? []);
        $tags          = $this->getTagsFromIds($_POST['product-tag'] ?? []);
        $prodBrand     = (int) $_POST['product-brand'] ?? null;
        $productClientId = $_POST['product-client-id'] ?? '';
        $images        = [];

        $shippingLocal = ($shippingLocal == '')? null: (float) $shippingLocal;
        $shippingNationwide = ($shippingNationwide == '')? null: (float) $shippingNationwide;
        $shippingInternational = ($shippingInternational == '')? null: (float) $shippingInternational;

        if($prodBrand != null && $prodBrand != '')
            $brand = $brandModel->getBrand($prodBrand);

        $shipping   = new Shipping($shippingLocal, $shippingNationwide, $shippingInternational);
        $product = new Product($id, $name, $code, $description, 
            $price, $brand, $categories, $tags, $isPublished, 
            $images, $quantity, $shipping, $condition);

        $this->model->updateProduct($product);

        // Redirect to the added product.
        Session::getInstance()->setMessage("Product Successfully Updated");
        $response->sendToPage('/product/' . $id);
    }

    public function deleteProduct(Request $request, Response $response){
        throw new NotImplementedException();
    }

    protected function renderProduct(string $route, ?Product $product = null, array $productPlugins = []){
        $this->addTwigFilter('isInArray', function(mixed $value, array $arr): bool{
            $res = \array_search($value, $arr);
            if($res === false)
                return false;
            return true;
        });

        $this->render(
            'products/product', 
            [
                'route' => $route,
                'pageTitle' => 'Product',
                'product' => $product,
                'categories' => (new CategoryModel(Database::getInstance()))->getAllCategories(),
                'tags' => (new TagModel(Database::getInstance()))->getAllTags(),
                'brands' => (new BrandModel(Database::getInstance()))->getAllBrands(),
                'productPlugins' => $productPlugins,
                'plugins' => PluginManager::getInstance()->getPlugins()
            ]
        );
    }
}