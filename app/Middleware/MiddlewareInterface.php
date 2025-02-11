<?php
declare(strict_types = 1);

namespace App\Middleware;

use App\Request;
use App\Response;

interface MiddlewareInterface{
    public function handle(Request $request, Response $response, $next);
}