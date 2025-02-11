<?php
declare(strict_types = 1);

namespace App;

use App\Middleware\MiddlewareInterface;
use App\MiddlewareTrait;

class Route{
    /** @var array<MiddlewareInterface> $middleware All the routes middleware. */
    public array $middleware = [];

    /** @var string $method Request method for the route. */
    public string $method;

    /** @var string $route Route to go to. */
    public string $route;

    /** @var string $controller Controller class for the route. */
    public string $controller;

    /** @var string $action Controller action */
    public string $action;

    use MiddlewareTrait;

    public function __construct(string $method, string $route, string $controller, string $action){
        $this->method = $method;
        $this->route  = $route;
        $this->controller = $controller;
        $this->action = $action;
    }

    public function getMiddleware(): array{
        return $this->middleware;
    }
}