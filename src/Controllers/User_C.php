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
        $csrfToken = generateCsrfToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF Verification
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF token validation failed.");
            }

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
            
            // Password Complexity Regex: Min 8 chars, at least one uppercase, one lowercase, one number, one special char
            $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            if (!preg_match($regex, $password)) {
                $errors[] = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.";
            }

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

    public function ajax_check_username() {
        $username = sanitize($_GET['username'] ?? '');
        $user = new User();
        $available = !$user->existsByUsername($username);
        header('Content-Type: application/json');
        echo json_encode(['available' => $available]);
        exit;
    }

    public function ajax_check_email() {
        $email = sanitize($_GET['email'] ?? '');
        $user = new User();
        $available = !$user->existsByEmail($email);
        header('Content-Type: application/json');
        echo json_encode(['available' => $available]);
        exit;
    }



    /* --------------------------------------------------------------
       Login
    -------------------------------------------------------------- */
    public function login() {
        $errors = [];
        $old    = [];
        $csrfToken = generateCsrfToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF Verification
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF token validation failed.");
            }

            $old['email'] = sanitize($_POST['email'] ?? '');
            $password     = $_POST['password'] ?? '';
            $ip           = getIpAddress();

            if (!$old['email'])        $errors[] = "Email is required.";
            if (!$password)            $errors[] = "Password is required.";

            if (empty($errors)) {
                $user = new User();
                
                // Rate Limiting
                $attempts = $user->getRecentAttempts($old['email'], $ip);
                if ($attempts >= 5) {
                    $errors[] = "Too many failed login attempts. Please try again in 15 minutes.";
                } else {
                    $loginResult = $user->login($old['email'], $password);

                    if ($loginResult === true) {
                        $user->clearLoginAttempts($old['email'], $ip);
                        redirect('?page=Home');  // Redirect to home/dashboard page
                    } elseif ($loginResult === 'suspended') {
                        $errors[] = "Your account has been suspended. Please contact support.";
                    } else {
                        $user->recordLoginAttempt($old['email'], $ip);
                        $errors[] = "Invalid email or password. (" . (4 - $attempts) . " attempts remaining)";
                    }
                }
            }
        }

        include "views/User/Login.php";
    }

    // ... (existing helper methods if needed)

    // --- Admin Methods ---

    private function ensureAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            redirect('?page=Home');
            exit;
        }
    }

    public function admin_index() {
        $this->ensureAdmin();
        $userModel = new User();
        $users = $userModel->getAllUsers();
        include __DIR__ . '/../Views/Admin/Users/index.php';
    }

    public function toggle_status() {
        $this->ensureAdmin();
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        
        // Prevent banning yourself
        if ($id == $_SESSION['user_id']) {
            redirect('?page=admin_users'); // or show error
            return;
        }

        $userModel = new User();
        $newStatus = ($status === 'active') ? 'suspended' : 'active';
        $userModel->updateStatus($id, $newStatus);
        
        redirect('?page=admin_users');
    }

    public function promote() {
        $this->ensureAdmin();
        $id = $_POST['id'] ?? 0;
        
        $userModel = new User();
        $userModel->promoteToAdmin($id);
        
        redirect('?page=admin_users');
    }

    public function destroy() {
        $this->ensureAdmin();
        $id = $_POST['id'] ?? 0;
        
        // Prevent deleting yourself
        if ($id == $_SESSION['user_id']) {
            redirect('?page=admin_users');
            return;
        }

        $userModel = new User();
        $userModel->deleteUser($id);
        
        redirect('?page=admin_users');
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



    


