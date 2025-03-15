<?php
declare(strict_types = 1);

namespace App\Api\V1\Controllers;

use App\Api\V1\Controllers\ApiBaseController;
use App\Crypto;
use App\Request;
use App\Models\UserModel;
use App\Response;
use App\Database;
use App\Api\V1\JWT;

class ApiAuthController extends ApiBaseController
{
    protected UserModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new UserModel(Database::getInstance());
    }

    /**
     * Login a user and create token.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function login(Request $request, Response $response){
        $data = json_decode($request->getBody(), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->model->getUserByUsername($username);
        if ($user == null){
            $response->json(['message' => 'Unauthorized'], 401);
            $response->send();
            return;
        }

        if (!Crypto::verify($password, $user->getPassword())){
            $response->json(['message' => 'Unauthorized'], 401);
            $response->send();
            return;
        }

        $jwt = new JWT(JWT_SECRET);
        $JWT = $jwt->generate($user->getId(), JWT_EXPIRATION);

        $response->json([
        'message' => 'Authorized',
        'token' => $JWT
        ], 200);
        $response->send();
    }
}