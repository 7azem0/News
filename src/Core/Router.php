<?php
class Router {
    public static function route($page) {
        switch ($page) {
            case "article":
                include "controllers/ArticleController.php";
                (new ArticleController())->index();
                break;

            case "login":
                include "controllers/UserController.php";
                (new UserController())->loginPage();
                break;

            default:
                include "views/home.php";
        }
    }
}
