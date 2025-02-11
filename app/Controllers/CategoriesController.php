<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Database;
use App\Models\Category;
use App\Models\CategoryModel;

class CategoriesController extends BaseController{
    private CategoryModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new CategoryModel(Database::getInstance());
    }

    public function getCategories(Request $request, Response $response){
        $categories = $this->model->getAllCategories();

        $this->render(
            'categories/categories', 
            [
                'route' => $request->getFirstUriPart(), 
                'categories' => $categories
            ]
        );
    }

    public function getCategory(Request $request, Response $response){
        $catId = $this->getPageOrLimitNum($request->getSecondUriPart());
        if(!$catId) $response->sendToPage('/categories');
        $category = $this->model->getCategory($catId);

        $this->renderCategory($request->getFirstUriPart(), $category);
    }

    public function getCreateCategory(Request $request, Response $response){
        $this->renderCategory($request->getUri());
    }

    public function createCategory(Request $request, Response $response){
        $catName = $_POST['category-name'];
        $catSlug = $_POST['category-slug'];
        $catParentId = $_POST['category-parent'];
        $catIsPublished = (bool) $_POST['category-status'];

        if($catParentId == ""){
            $catParentId = null;
        } else {
            $catParentId = (int) $catParentId;
        }
        $category = $this->model->addCategory($catName, $catSlug, $catParentId, $catIsPublished);
        $response->sendToPage('/category/' . $category->getId());
    }

    public function updateCategory(Request $request, Response $response){
        $catId = (int) $_POST['category-id'];
        $catName = $_POST['category-name'];
        $catSlug = $_POST['category-slug'];
        $catParent = $_POST['category-parent'];
        $catIsPublished = (bool) $_POST['category-status'];

        $catParent = ($catParent == '')? null: (int) $catParent;

        $isUpdated = $this->model->updateCategory($catId, $catName, $catSlug, $catParent, $catIsPublished);
        $category = ($isUpdated)
                    ? new Category($catId, $catName, $catSlug, $catParent, $catIsPublished)
                    : $this->model->getCategory($catId);
        
        $this->renderCategory($request->getFirstUriPart(), $category);
    }

    public function deleteCategory(Request $request, Response $response){
        $catId = $_GET['category-id'];
        $this->model->deleteCategory((int) $catId);
        $response->sendToPage('/categories');
    }

    private function renderCategory(string $route, ?Category $category = null){
        $this->render(
            'categories/category',
            [
                'route' => $route,
                'category' => $category,
                'categories' => $this->model->getAllCategories()
            ]
        );
    }
}