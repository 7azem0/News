<?php
if (!function_exists('redirect')) {
   function redirect($path) {
    header("Location: index.php" . $path, true, 303);
    exit;
}

}

if (!function_exists('sanitize')) {
    function sanitize($str) {
        return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
    }
}

// --- CSRF Protection ---
if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verifyCsrfToken')) {
    function verifyCsrfToken($token) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}

// --- Rate Limiting Helper ---
if (!function_exists('getIpAddress')) {
    function getIpAddress() {
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}
