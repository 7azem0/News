<?php
require "config/DataBase_Connection.php";
require "core/Router.php";
require "core/Helpers.php";

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$selectedLang = $_SESSION['lang'] ?? 'en';

$page = $_GET['page'] ?? "";

Router::route($page, $selectedLang);
