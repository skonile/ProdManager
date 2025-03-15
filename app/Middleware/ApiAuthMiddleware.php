<?php
declare(strict_types = 1);

namespace App\Middleware;

use App\Request;
use App\Response;
use App\Api\V1\JWT;

class ApiAuthMiddleware implements MiddlewareInterface{
    /**
     * Handles the incoming request by verifying the JWT token.
     *
     * @param Request $request The incoming HTTP request.
     * @param Response $response The HTTP response to be sent.
     * @param callable $next The next middleware to be called.
     *
     * This method extracts the JWT from the request, verifies it, and retrieves the payload.
     * If the JWT is valid, it sets the user ID in the request parameters and calls the next middleware.
     * If the JWT is invalid, it sends an unauthorized error response and terminates the execution.
     */
    public function handle(Request $request, Response $response, $next){
        $jwt = new JWT(JWT_SECRET);
        $jwtFromRequest = $jwt->getJWTFromRequest($request);
        if($jwtFromRequest && $jwt->verify($jwtFromRequest)){
            $payload = $jwt->getPayload($jwtFromRequest);
            $request->setParam('userId', (string) $payload['sub']);
            return $next($request, $response);
        } else {
            $response->json(['error' => 'Unauthorized'], 401);
            $response->send();
            exit;
        }
    }
}