<?php
// /Router.php

class Router {
    const CONTROLLERS_PATH = __DIR__ . '/../Controllers/';
    const VIEWS_PATH = __DIR__ . '/../Views/';

    public static function route(string $page, string $selectedLang = 'en'): void {
        switch ($page) {
            // Home page
            case "":
            case "index":
            case "Home":
                include "Views/Home.php";
                break;

            // Article routes
            case "article":
                require_once __DIR__ . '/../Controllers/Article_C.php';
                $controller = new ArticleController();

                 if (isset($_GET['id'])) {
                    $controller->index(); 
                } else {
                    $controller->index(); 
                }
                break;

            // News API routes 
            case "news":
                require_once __DIR__ . '/../Controllers/Article_C.php';
                $controller = new ArticleController();
                $controller->news('us', 'technology');
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
