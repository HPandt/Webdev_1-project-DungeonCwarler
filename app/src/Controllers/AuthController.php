<?php

namespace App\Controllers;

use App\Core\Repository;
use App\Exceptions\UserFacingException;
use App\Services\AuthService;
use App\Models\Enums\Roles;
use App\Models\UserModel;

class AuthController
{
    
    private AuthService $authService;
    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function loginForm()
    {
        // load the Auth login view
        require(__DIR__ . '/../Views/Auth/index.php');      
    }

    public function login(){
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)){
            $error = "Invalid email or password";
            require(__DIR__ . '/../Views/Auth/index.php');
            return;
        }

        $user = $this->authService->login($email, $password);
        if($user){
            // Successful login
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;

            // Redirect by role
            if (strtolower($user->role->value) === 'admin') {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /game/dashboard');
            }
            exit();
        }else{
            return require(__DIR__ . '/../Views/Auth/index.php');
        }
    }

    public function registerForm()
    {
        // load the Auth register view
        require(__DIR__ . '/../Views/Auth/register.php');      
    }

    public function register(){
        try {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = new UserModel(
                id: 0,
                name: $username,
                email: $email,
                password_hash: $password,
                role: Roles::player
            );

            $this->authService->createUser($user);

            header('Location: /');
            exit();
        } catch (UserFacingException $th) {
            throw new UserFacingException('Failed to create user' . $th->getMessage());
        }
    }

    public function logout(){
        $_SESSION = [];
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        header('Location: /');
       
        exit();
    }
}
