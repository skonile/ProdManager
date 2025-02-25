<?php
declare(strict_types = 1);

use App\App;
use App\Request;
use App\Response;
use App\Router;
use App\Controllers\AuthController;
use App\Controllers\PageController;
use App\Controllers\TagsController;
use App\Controllers\UserController;
use App\Controllers\MediaController;
use App\Controllers\BrandsController;
use App\Controllers\PluginsController;
use App\Controllers\ProductController;
use App\Controllers\ProductsController;
use App\Controllers\CategoriesController;

require_once '../vendor/autoload.php';
require_once '../config/config.php';

session_start();

$requestUri     = $_SERVER['REQUEST_URI'];
$requestMethod  = $_SERVER['REQUEST_METHOD'];
$request        = new Request($requestMethod, $requestUri);
$response       = new Response();
$router         = new Router($request, $response);
$app            = new App($router, $request, $response);

// Add global middleware
$router->middleware('Auth');

// Auth Pages
$router->get('/login', AuthController::class, 'getLogin');
$router->post('/login', AuthController::class, 'login');
$router->get('/logout', AuthController::class, 'logout');

// Products Routes
$router->get('/products', ProductsController::class, 'getProducts');
$router->get('/products/{page}', ProductsController::class, 'getProducts');
$router->get('/products/{page}/{limit}', ProductsController::class, 'getProducts');
$router->get('/product/{id}', ProductController::class, 'getProduct');
$router->get('/product/create', ProductController::class, 'getCreateProduct');
$router->post('/product/create', ProductController::class, 'createProduct');
$router->post('/product/update', ProductController::class, 'updateProduct');
$router->get('/product/delete', ProductController::class, 'deleteProduct');

// Images Routes
$router->post('/media/image/upload', MediaController::class, 'uploadImage');
$router->post('/media/image/delete', MediaController::class, 'deleteImage');

// Categories Routes
$router->get('/categories', CategoriesController::class, 'getCategories');
$router->get('/categories/{page}', CategoriesController::class, 'getCategories');
$router->get('/categories/{page}/{limit}', CategoriesController::class, 'getCategories');
$router->get('/category/{id}', CategoriesController::class, 'getCategory');
$router->get('/category/create', CategoriesController::class, 'getCreateCategory');
$router->post('/category/create', CategoriesController::class, 'createCategory');
$router->post('/category/update', CategoriesController::class, 'updateCategory');
$router->get('/category/delete', CategoriesController::class, 'deleteCategory');

// Tags Routes
$router->get('/tags', TagsController::class, 'getTags');
$router->get('/tags/{page}', TagsController::class, 'getTags');
$router->get('/tags/{page}/{limit}', TagsController::class, 'getTags');
$router->get('/tag/{id}', TagsController::class, 'getTag');
$router->get('/tag/create', TagsController::class, 'getCreateTag');
$router->post('/tag/create', TagsController::class, 'createTag');
$router->post('/tag/update', TagsController::class, 'updateTag');
$router->get('/tag/delete', TagsController::class, 'deleteTag');

// Brands Routes
$router->get('/brands', BrandsController::class, 'getBrands');
$router->get('/brands/{page}', BrandsController::class, 'getBrands');
$router->get('/brands/{page}/{limit}', BrandsController::class, 'getBrands');
$router->get('/brand/{id}', BrandsController::class, 'getBrand');
$router->get('/brand/create', BrandsController::class, 'getCreateBrand');
$router->post('/brand/create', BrandsController::class, 'createBrand');
$router->post('/brand/update', BrandsController::class, 'updateBrand');
$router->get('/brand/delete', BrandsController::class, 'deleteBrand');

// Plugins Routes
$router->get('/plugins', PluginsController::class, 'getPlugins');
$router->get('/plugin/{plugin}', PluginsController::class, 'getPlugin');
$router->get('/plugin/add', PluginsController::class, 'getAddPlugin');
$router->post('/plugin/add', PluginsController::class, 'addPlugin');
$router->post('/plugin/update', PluginsController::class, 'updatePlugin');
$router->get('/plugin/delete', PluginsController::class, 'deletePlugin');

// Users Routes
$router->get('/users', UserController::class, 'getUsers');
$router->get('/user/{id}', UserController::class, 'getUser');
$router->get('/user/add', UserController::class, 'getAddUser');
$router->post('/user/add', UserController::class, 'addUser');
$router->post('/user/update/{id}', UserController::class, 'updateUser');
$router->get('/user/delete/{id}', UserController::class, 'deleteUser');

// Other Software Pages Routes
$router->get('/', PageController::class, 'getHome');
$router->get('/settings', PageController::class, 'getSettings');
$router->get('/about', PageController::class, 'getAbout');

echo $app->run();