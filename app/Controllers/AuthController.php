<?php 
declare(strict_types = 1);

namespace App\Controllers;

use App\Crypto;
use App\Database;
use App\Models\UserLevel;
use App\Models\UserModel;
use App\Request;
use App\Response;
use App\Session;

class AuthController extends BaseController{
    protected UserModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new UserModel(Database::getInstance());
    }

    public function getLogin(Request $request, Response $response){
        $this->render('auth/login');
    }

    public function login(Request $request, Response $response){
        $username = $request->getPOST()['username'] ?? "";
        $password = $request->getPOST()['password'] ?? "";

        if($username == "" || $password == ""){
            $this->renderLoginWithErrors($username);
            return;
        }

        $user = $this->model->getUserByUsername($username);
        if($user == null){
            $this->renderLoginWithErrors($username);
            return;
        }

        if(!Crypto::verify($password, $user->getPassword())){
            $this->renderLoginWithErrors($username);
            return;
        }

        $this->setLogginSessions($user->getId(), $user->getLevel());
        $response->sendToHomePage();
    }

    public function getRegister(Request $request, Response $response){
        $this->render('auth/register');
    }

    public function register(Request $request, Response $response){
        $firstname = $request->getPOST()['fname'] ?? '';
        $lastname  = $request->getPOST()['lname'] ?? '';
        $username  = $request->getPOST()['username'] ?? '';
        $email     = $request->getPOST()['email'] ?? '';
        $password  = $request->getPOST()['password'] ?? '';

        if(empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password)){
            $error = 'All fields have to be filled.';
            $this->renderRegisterWithErrors($error, $firstname, $lastname, $username, $email);
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = 'Enter a valid email address.';
            $this->renderRegisterWithErrors($error, $firstname, $lastname, $username, $email);
            return;
        }
            
        $user = $this->model->getUserByUsername($username);
        $doesUserExist = !is_null($user);
        if($doesUserExist){
            $error = "Username {$username} is already taken.";
            $this->renderRegisterWithErrors($error, $firstname, $lastname, $username, $email);
            return;
        }

        $hashedPass = Crypto::encrypt($password);
        if(!$this->model->addUser($firstname, $lastname, $username, $email, $hashedPass, UserLevel::Viewer)){
            $error = 'Could not create a new user. please try again.';
            $this->renderRegisterWithErrors($error, $firstname, $lastname, $username, $email);
            return;
        }

        $userId = Database::getInstance()->getInsertId();
        $this->setLogginSessions($userId, UserLevel::Viewer);
        $response->sendToHomePage();
    }

    protected function setLogginSessions(int $userId, UserLevel $userLevel){
        $session = Session::getInstance();
        $session->setIsLoggedIn($userId);
        $session->set('userlevel', $userLevel->value);
    }

    public function logout(Request $request, Response $response){
        session_unset();
        session_destroy();
        $response->sendToHomePage();
    }

    protected function renderLoginWithErrors(string $username){
        $this->render(
            'auth/login', 
            [
                'errors' => ["Invalid username/password"], 
                'username' => $username
            ]
        );
    }

    protected function renderRegisterWithErrors(string $errorMsg, string $firstname, string $lastname, string $username, string $email){
        $this->render(
            'auth/register',
            [
                'errors' => [$errorMsg],
                'firstname' => $firstname,
                'lastname' => $lastname,
                'username' => $username,
                'email' => $email
            ]
        );
    }
}