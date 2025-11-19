<?php
require "config/db.php";
require "core/Router.php";
require "core/Helpers.php";

$page = $_GET['page'] ?? "";

Router::route($page);
