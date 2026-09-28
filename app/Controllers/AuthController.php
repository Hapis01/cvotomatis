<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\AuthHelper;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (AuthHelper::check()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }

    public function processLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $users = $userModel->where('email', $email);

        if (count($users) > 0) {
            $user = $users[0];
            if (password_verify($password, $user->password)) {
                AuthHelper::login($user);
                $this->redirect('/dashboard');
            }
        }

        // Failed login
        $this->view('auth/login', ['error' => 'Invalid email or password']);
    }

    public function logout()
    {
        AuthHelper::logout();
        $this->redirect('/login');
    }
}
