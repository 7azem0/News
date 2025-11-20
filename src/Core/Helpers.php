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
