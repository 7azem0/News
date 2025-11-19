<?php
require_once "models/User.php";

class UserController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username']);
            $email = sanitize($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $user = new User();
            if ($user->create($username, $email, $password)) {
                redirect('index.php');
            } else {
                echo "Error creating user.";
            }
        }
        include "views/user/register.php";
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];

            $user = new User();
            if ($user->login($email, $password)) {
                redirect('index.php');
            } else {
                echo "Invalid login.";
            }
        }
        include "views/user/login.php";
    }
}
