<?php
require_once "models/User.php";
require_once "Core/Helpers.php";

class UserController {

    /* --------------------------------------------------------------
       Registration
    -------------------------------------------------------------- */
    public function register() {
        $errors = [];
        $old    = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Old values
            $old['username'] = sanitize($_POST['username'] ?? '');
            $old['email']    = sanitize($_POST['email'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirm         = $_POST['confirm_password'] ?? '';

            // Validation
            if (!$old['username'])           $errors[] = "Username is required.";
            if (!$old['email'])              $errors[] = "Email is required.";
            elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL))
                                             $errors[] = "Invalid email format.";
            if (!$password)                  $errors[] = "Password is required.";
            if ($password !== $confirm)      $errors[] = "Passwords do not match.";

            // If ok, create user
            if (empty($errors)) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $user   = new User();

                if ($user->create($old['username'], $old['email'], $hashed)) {
                    redirect('?page=Login');   // Router-compatible redirect
                } else {
                    $errors[] = "User already exists or email is taken.";
                }
            }
        }

        include "views/User/Registeration.php";
    }



    /* --------------------------------------------------------------
       Login
    -------------------------------------------------------------- */
    public function login() {
        $errors = [];
        $old    = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $old['email'] = sanitize($_POST['email'] ?? '');
            $password     = $_POST['password'] ?? '';

            if (!$old['email'])        $errors[] = "Email is required.";
            if (!$password)            $errors[] = "Password is required.";

            if (empty($errors)) {
                $user = new User();

                if ($user->login($old['email'], $password)) {
                    redirect('?page=Home');  // Redirect to home/dashboard page
                } else {
                    $errors[] = "Invalid email or password.";
                }
            }
        }

        include "views/User/Login.php";
    }



    /* --------------------------------------------------------------
       Forgot Password
    -------------------------------------------------------------- */
    public function forgotPassword() {
        $errors = [];
        $message = null;
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['email'] = sanitize($_POST['email'] ?? '');

            if (!$old['email']) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            } else {
                // Placeholder
                $message = "If this email exists, a reset link has been sent.";
            }
        }

        include "views/User/ForgotPassword.php";
    }

    /* --------------------------------------------------------------
       Logout
    -------------------------------------------------------------- */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        session_destroy();
        redirect('?page=Home');
    }

}
