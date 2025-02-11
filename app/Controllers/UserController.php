<?php 
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Database;
use App\Models\UserModel;

class UserController extends BaseController{
    protected UserModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new UserModel(Database::getInstance());
    }

    /**
     * Get the users that the admin can control.
     * 
     * @param Request $request
     * @param Response $response
     */
    function getUsers(Request $request, Response $response){
        $users = $this->model->getAllUsers();
        $this->render('users/users', ['users' => $users]);
    }
}