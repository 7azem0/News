<?php
// /Router.php

class Router {
    public static function route($page, $selectedLang = 'en') {
        switch ($page) {
            // Home page
            case "":
            case "index":
            case "Home":
                include "Views/Home.php";
                break;

            // Article routes
            case "article":
                include "Controllers/Article_C.php";
                (new ArticleController())->index();
                break;

            // Search routes
            case "search":
                include "Controllers/Search_C.php";
                (new SearchController())->index();
                break;

            // User routes
            case "Login":
                include "Controllers/User_C.php";
                (new UserController())->login();
                break;

            case "register":
                include "Controllers/User_C.php";
                (new UserController())->register();
                break;

            case "forgot-password":
                include "Controllers/User_C.php";
                (new UserController())->forgotPassword();
                break;

            case "Account":
                include "Controllers/User_C.php";
                (new UserController())->account();
                break;

            case "logout":
                include "Controllers/User_C.php";
                (new UserController())->logout();
                break;

            case "subscribe":
                include "Controllers/Subscription_C.php";
                (new SubscriptionController())->subscribe();
                break;

            case "plans":
                include "Views/Subscription/Plans.php";
                break;

            default:
                http_response_code(404);
                echo "Page not found";
        }
    }
}
