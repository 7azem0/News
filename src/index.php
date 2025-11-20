<?php
require "config/DataBase_Connection.php";
require "core/Router.php";
require "core/Helpers.php";

$page = $_GET['page'] ?? "";

Router::route($page);
