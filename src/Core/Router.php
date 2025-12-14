<?php
// /Router.php

class Router {

    const CONTROLLERS_PATH = __DIR__ . '/../Controllers/';
    const VIEWS_PATH       = __DIR__ . '/../Views/';

    public static function route(string $page, string $selectedLang = 'en'): void {

        switch (strtolower($page)) {

            // Home
            case "":
            case "index":
            case "home":
                include self::VIEWS_PATH . "Home.php";
                break;

            // Articles (list + single)
            case "article":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->index();
                break;

            // News (external API)
            case "news":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->news(
                    $_GET['country'] ?? 'us',
                    $_GET['category'] ?? 'technology'
                );
                break;

            // Search
            case "search":
                require_once self::CONTROLLERS_PATH . "Search_C.php";
                (new SearchController())->index();
                break;

            // Auth & user
            case "login":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->login();
                break;

            case "register":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->register();
                break;

            case "forgot-password":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->forgotPassword();
                break;

            case "account":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->account();
                break;

            case "logout":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->logout();
                break;

            // Subscription
            case "subscribe":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->subscribe();
                break;

            case "plans":
                include self::VIEWS_PATH . "Subscription/Plans.php";
                break;

            // 404
            default:
                http_response_code(404);
                echo "Page not found";
        }
    }
}
