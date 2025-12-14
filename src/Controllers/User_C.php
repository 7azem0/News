<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Core/Helpers.php';

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
    /* --------------------------------------------------------------
       Logout
    -------------------------------------------------------------- */
    public function logout() {
        // التأكد من بدء الجلسة
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // تدمير الجلسة
        session_destroy();
        
        // التحويل للصفحة الرئيسية
        redirect('?page=Home');
    } // <--- لازم نقفل دالة الـ Logout هنا

    /* --------------------------------------------------------------
       Account Page
    -------------------------------------------------------------- */

    // Page Account Setting ( Y hazem Y ledr)
public function account() {
        // التأكد من بدء الجلسة
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // لو مش مسجل دخول، ارميه بره
        if (!isset($_SESSION['user_id'])) {
            redirect('?page=Login');
            exit;
        }

        $userModel = new User();
        $user_id = $_SESSION['user_id'];
        
        // هات بيانات المستخدم الحالية من الداتابيز
        $currentUser = $userModel->getUserById($user_id);

        $error = "";
        $success = "";

        // لو المستخدم داس على زرار Save
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_username = sanitize($_POST['username']);
            $new_email = sanitize($_POST['email']);
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // 1. أهم خطوة: التحقق من الباسورد الحالية
            if (empty($current_password)) {
                $error = "Current password is required to save changes.";
            } elseif (!password_verify($current_password, $currentUser['password'])) {
                $error = "Incorrect current password. Changes not saved.";
            } else {
                // --- الباسورد الحالية صح، نبدأ التعديل ---

                // أ) تعديل البيانات الشخصية (الاسم والإيميل)
                if ($new_username != $currentUser['username'] || $new_email != $currentUser['email']) {
                    if ($userModel->updateProfile($user_id, $new_username, $new_email)) {
                        // تحديث السيشـن بالبيانات الجديدة
                        $_SESSION['username'] = $new_username;
                        $_SESSION['email'] = $new_email;
                        $success = "Profile updated successfully.";
                        // تحديث المتغير عشان يظهر في الفورم فوراً
                        $currentUser['username'] = $new_username;
                        $currentUser['email'] = $new_email;
                    } else {
                        $error = "Failed to update profile.";
                    }
                }

                // ب) تعديل الباسورد (لو الخانات مش فاضية)
                if (!empty($new_password)) {
                    if ($new_password !== $confirm_password) {
                        $error = "New passwords do not match.";
                    } elseif (strlen($new_password) < 6) { // مثلاً أقل حاجة 6
                        $error = "New password must be at least 6 characters.";
                    } else {
                        // تشفير الباسورد الجديدة
                        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                        if ($userModel->updatePassword($user_id, $hashed)) {
                            $success .= " Password changed successfully.";
                        } else {
                            $error = "Failed to update password.";
                        }
                    }
                }
            }
        }

        // عرض الصفحة
        include "views/User/account.php";
    }

} 



    


