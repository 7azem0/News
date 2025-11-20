<?php
class Router {
    public static function route($page) {
        switch ($page) {
            case "article":
                include "controllers/Article_C.php";
                (new ArticleController())->index();
                break;

            case "login":
                include "controllers/UserController.php";
                (new UserController())->login();
                break;

            default:
                include "views/home.php";
        }
    }
}
