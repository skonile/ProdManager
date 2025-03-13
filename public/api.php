<?php
declare(strict_types = 1);

use \App\Header;

function api_var_dump($var){
    global $response;
    $response->json(['result' => $var]);
    $response->send();
    exit;
}

/** @var \App\Response $response */
$response->setHeader(new Header('Access-Control-Allow-Origin', '*'));

/** @var \App\Request $request */
if(!($request->getUri() == '/api/v1' || $request->getUri() == '/api/v1/login'))
    $router->middleware('ApiAuth');

/** @var \App\Router $router */
$router->get('/api/v1', App\Api\V1\Controllers\ApiIndexController::class, 'testGet');
$router->post('/api/v1', App\Api\V1\Controllers\ApiIndexController::class, 'testPost');
$router->put('/api/v1', App\Api\V1\Controllers\ApiIndexController::class, 'testPut');
$router->delete('/api/v1', App\Api\V1\Controllers\ApiIndexController::class, 'testDelete');

// Login
$router->post('/api/v1/login', App\Api\V1\Controllers\ApiAuthController::class, 'login');

// Products
$router->get('/api/v1/products', App\Api\V1\Controllers\ApiProductController::class, 'getProducts');
$router->post('/api/v1/products', App\Api\V1\Controllers\ApiProductController::class, 'addProduct');
$router->get('/api/v1/products/{id}', App\Api\V1\Controllers\ApiProductController::class, 'getProduct');
$router->put('/api/v1/products/{id}', App\Api\V1\Controllers\ApiProductController::class, 'updateProduct');
$router->delete('/api/v1/products/{id}', App\Api\V1\Controllers\ApiProductController::class, 'deleteProduct');
// Product images
$router->post('/api/v1/products/{id}/images', App\Api\V1\Controllers\ApiProductController::class, 'uploadImage');
$router->get('/api/v1/products/{id}/images/{imageId}', App\Api\V1\Controllers\ApiProductController::class, 'getImage');
$router->delete('/api/v1/products/{id}/images/{imageId}', App\Api\V1\Controllers\ApiProductController::class, 'deleteImage');

// Tags
$router->get('/api/v1/tags', App\Api\V1\Controllers\ApiTagController::class, 'getTags');
$router->post('/api/v1/tags', App\Api\V1\Controllers\ApiTagController::class, 'addTag');
$router->get('/api/v1/tags/{id}', App\Api\V1\Controllers\ApiTagController::class, 'getTag');
$router->put('/api/v1/tags/{id}', App\Api\V1\Controllers\ApiTagController::class, 'updateTag');
$router->delete('/api/v1/tags/{id}', App\Api\V1\Controllers\ApiTagController::class, 'deleteTag');

// Categories
$router->get('/api/v1/categories', App\Api\V1\Controllers\ApiCategoryController::class, 'getCategories');
$router->post('/api/v1/categories', App\Api\V1\Controllers\ApiCategoryController::class, 'addCategory');
$router->get('/api/v1/categories/{id}', App\Api\V1\Controllers\ApiCategoryController::class, 'getCategory');
$router->put('/api/v1/categories/{id}', App\Api\V1\Controllers\ApiCategoryController::class, 'updateCategory');
$router->delete('/api/v1/categories/{id}', App\Api\V1\Controllers\ApiCategoryController::class, 'deleteCategory');

// Users
$router->get('/api/v1/users', App\Api\V1\Controllers\ApiUserController::class, 'getUsers');
$router->post('/api/v1/users', App\Api\V1\Controllers\ApiUserController::class, 'addUser');
$router->get('/api/v1/users/{id}', App\Api\V1\Controllers\ApiUserController::class, 'getUser');
$router->put('/api/v1/users/{id}', App\Api\V1\Controllers\ApiUserController::class, 'updateUser');
$router->delete('/api/v1/users/{id}', App\Api\V1\Controllers\ApiUserController::class, 'deleteUser');

// Brands
$router->get('/api/v1/brands', App\Api\V1\Controllers\ApiBrandController::class, 'getBrands');
$router->post('/api/v1/brands', App\Api\V1\Controllers\ApiBrandController::class, 'addBrand');
$router->get('/api/v1/brands/{id}', App\Api\V1\Controllers\ApiBrandController::class, 'getBrand');
$router->put('/api/v1/brands/{id}', App\Api\V1\Controllers\ApiBrandController::class, 'updateBrand');
$router->delete('/api/v1/brands/{id}', App\Api\V1\Controllers\ApiBrandController::class, 'deleteBrand');


