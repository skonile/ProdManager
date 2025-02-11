<?php
declare(strict_types = 1);

namespace App;

use App\Controllers\ErrorController;
use \App\Plugins\PluginManager;
use \App\Exceptions\ViewExceptions\ViewNotFoundException;
use \App\Exceptions\RouteNotFoundException;

class App{
    public Router $router;
    public Request $request;
    public Response $response;

    public function __construct(Router $router, Request $request, Response $response){
        $this->router   = $router;
        $this->request  = $request;
        $this->response = $response;
    }

    public function run(){
        $this->loadPlugins();
        $this->resolveRoute();
    }

    public function loadPlugins(){
        PluginManager::getInstance()->loadPlugins();
    }

    public function resolveRoute(){
        try{
            return $this->router->resolve();
        } catch(ViewNotFoundException|RouteNotFoundException){
            $errorController = new ErrorController();
            return $errorController->getError404($this->request, $this->response);
        }
    }
}