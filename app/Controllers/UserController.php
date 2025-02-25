<?php 
declare(strict_types = 1);

namespace App\Controllers;

use App\Crypto;
use App\Request;
use App\Response;
use App\Database;
use App\Models\UserLevel;
use App\Models\UserModel;
use App\Session;

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
        $this->render(
            'users/users', 
            [
                'users' => $users,
                'currentUserId' => Session::getInstance()->get('user-id')
            ] 
        );
    }

    function getUser(Request $request, Response $response){
        $userId = (int) $request->getSecondUriPart();
        if($userId < 1){
            Session::getInstance()->setMessage("Invalid user id");
            $response->sendToPage('/users');
        }

        $user = $this->model->getUser($userId);
        if($user == null){
            Session::getInstance()->setMessage('User with id ' . $userId . ' does not exist');
            $response->sendToPage('/users');
        }

        $this->render(
            'users/user', 
            [
                'user' => $user,
                'newOrView' => $request->getSecondUriPart(),
                'userLevels' => UserLevel::all(),
                'currentUserLevel' => $user->getLevel()->value,
                'currentUserId' => Session::getInstance()->get('user-id')
            ]
        );
    }

    function getAddUser(Request $request, Response $response){
        $this->render(
            'users/user', 
            [
                'userLevels' => UserLevel::all(),
                'currentUserId' => Session::getInstance()->get('user-id'),
                'isNewUser' => true
            ]
        );
    }

    /**
     * Handles the request to add a new user.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * @return void
     */
    public function addUser(Request $request, Response $response){
        $firstname = $request->post('fname');
        $lastname  = $request->post('lname');
        $username  = $request->post('username');
        $email     = $request->post('email');
        $password  = $request->post('password');
        $userLevel = $request->post('level');
        $errors = [];

        if($firstname == '' || $lastname == '' || $username == '' || $email == '' || $password == '' || $userLevel == '')
            $errors[] = 'All fields must be filled';
            
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Invalid email address';

        if(strlen($password) < 6)
            $errors[] = 'Password must be at least 6 characters long';

        if(strlen($username) < 4)
            $errors[] = 'Username must be at least 4 characters long';

        if(count($errors) > 0){
            $this->render(
                'users/user', 
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'username' => $username,
                    'email' => $email,
                    'level' => $userLevel,
                    'errors' => $errors,
                    'isNewUser' => true
                ]
            );
            return;
        }

        $isAdded = $this->model->addUser(
            $firstname, 
            $lastname, 
            $username, 
            $email, 
            $password, 
            UserLevel::fromString($userLevel)
        );
        if(!$isAdded){
            Session::getInstance()->setMessage('Failed to add user');
            $response->sendToPage('/user/add');
            return;
        }

        Session::getInstance()->setMessage('User added successfully');
        $response->sendToPage('/users');
    }

    public function updateUser(Request $request, Response $response){
        $userId    = (int) $request->post('user-id');
        $firstname = $request->post('fname');
        $lastname  = $request->post('lname');
        $username  = $request->post('username');
        $email     = $request->post('email');
        $password  = $request->post('password');
        $userLevel = $request->post('level');
        $errors = [];

        if($password == '')
            $password = null;

        $user = $this->model->getUser($userId);
        if($userId == '' || $userId < 0 || $user == null){
            Session::getInstance()->setMessage('Bad user id. could not update user.');
            $response->sendToPage('/users');
            return;
        }

        if($firstname == '' || $lastname == '' || $username == '' || $email == '' || $userLevel == '')
            $errors[] = 'All fields must be filled';
            
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Invalid email address';

        if($password != null && strlen($password) < 6)
            $errors[] = 'Password must be at least 6 characters long';

        if(strlen($username) < 4)
            $errors[] = 'Username must be at least 4 characters long';

        if(count($errors) > 0){
            $this->render(
                'users/user', 
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'username' => $username,
                    'email' => $email,
                    'level' => $userLevel,
                    'errors' => $errors
                ]
            );
            return;
        }
        
        if($password != null)
            $password = Crypto::encrypt($password);
        else
            $password = $user->getPassword();

        $isUpdated = $this->model->updateUser(
            $userId,
            $firstname, 
            $lastname, 
            $username, 
            $email, 
            $password, 
            UserLevel::fromString($userLevel)
        );
        if(!$isUpdated){
            Session::getInstance()->setMessage('Failed to update user');
            $response->sendToPage('/user/add');
            return;
        }

        Session::getInstance()->setMessage('User successfully updated');
        $response->sendToPage('/users');
    }

    /**
     * Add a new user to the system.
     * 
     * @param Request $request
     * @param Response $response
     * @return void
     */
    function deleteUser(Request $request, Response $response){
        $userId = (int) $request->getThirdUriPart();
        if($userId < 1){
            Session::getInstance()->setMessage("Invalid user id");
            $response->sendToPage('/users');
        }

        $user = $this->model->getUser($userId);
        if($user == null){
            Session::getInstance()->setMessage('User with id ' . $userId . ' does not exist');
            $response->sendToPage('/users');
        }

        $isRemoved = $this->model->removeUser($userId);
        if(!$isRemoved)
            Session::getInstance()->setMessage('Failed to delete user');
        else 
            Session::getInstance()->setMessage('User deleted successfully');
        $response->sendToPage('/users');
    }
}