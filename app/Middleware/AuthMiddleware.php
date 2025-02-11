<?php
declare(strict_types = 1);

namespace App\Middleware;

use App\Request;
use App\Response;

class AuthMiddleware implements MiddlewareInterface{
    public function handle(Request $request, Response $response, $next){
        if($request->getSession()->isLoggedIn()){
            return $next($request, $response);
        } else {
            $uri = $request->getUri();
            if($uri == '/login' || $uri == '/register')
                return $next();
            return $response->sendToPage('/login');
        }
    }
}