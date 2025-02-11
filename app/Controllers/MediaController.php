<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Database;
use App\Models\ProductModel;
use App\Controllers\BaseController;

class MediaController extends BaseController{
    public function __construct(){
        parent::__construct();
    }

    public function uploadImage(Request $request, Response $response){
        $image = $_FILES['product-image'];
        if($this->isImageSafe()){
            $imageNameArr = explode('-', $image['name']);
            if($imageNameArr[0] == 'product'){
                $prodId = (int) $imageNameArr[1];
                $productModel = new ProductModel(Database::getInstance());
                if(!$productModel->productExists($prodId)) return;
                move_uploaded_file($image['tmp_name'], PRODUCT_IMAGES_PATH . $image['name']);
                $productModel->addProductImages($prodId, [$image['name']]);
            } else {
                move_uploaded_file($image['tmp_name'], TMP_UPLOADED_FILES . $image['name']);
            }
            return;
        }
        echo 'Image/File not uploaded due to safty reasons.';
    }

    private function isImageSafe(): bool{
        return true;
    }

    public function deleteImage(Request $request, Response $response){
        $path = (!isset($_POST['product-id']))? TMP_UPLOADED_FILES: PRODUCT_IMAGES_PATH;
        $name = $_POST['image-name'] ?? '';

        if($name == ''){
            echo -1;
            return;
        }

        if(!$this->removeImageFromDB($name)){
            echo 0;
            return;
        }

        // Remove the image from the disk.
        $filename = $path . $name;
        if(file_exists($filename)){
            echo ((unlink($filename))? 1: 0);
        }
    }
    
    private function removeImageFromDB(string $imageName): bool{
        $prodModel = new ProductModel(Database::getInstance());
        return $prodModel->removeImage($imageName);
    }
}