<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/config/DataBase_Connection.php';
require __DIR__ . '/core/Router.php';
require __DIR__ . '/core/Helpers.php';
require __DIR__ . '/Controllers/Article_C.php';

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$selectedLang = $_SESSION['lang'] ?? 'en';

$page = $_GET['page'] ?? "";

Router::route($page, $selectedLang);
