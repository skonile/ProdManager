<?php
declare(strict_types = 1);

use App\App;
use App\Request;
use App\Response;
use App\Router;

require_once '../vendor/autoload.php';
require_once '../config/config.php';

$requestUri     = $_SERVER['REQUEST_URI'];
$requestMethod  = $_SERVER['REQUEST_METHOD'];
$request        = new Request($requestMethod, $requestUri);
$response       = new Response();
$router         = new Router($request, $response);
$app            = new App($router, $request, $response);

if(strpos($requestUri, '/api') === 0)
    require_once 'api.php';
else
    require_once 'web.php';

echo $app->run();