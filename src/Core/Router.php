<?php
// /Router.php

class Router {

    const CONTROLLERS_PATH = __DIR__ . '/../Controllers/';
    const VIEWS_PATH       = __DIR__ . '/../Views/';

    public static function route(string $page, string $selectedLang = 'en'): void {

        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Define public pages (accessible without login)
        $publicPages = ['', 'index', 'home', 'login', 'register', 'check_username', 'check_email', 'forgot-password'];
        
        // Check if user is logged in for protected pages
        if (!in_array(strtolower($page), $publicPages) && !isset($_SESSION['user_id'])) {
            // Redirect to login page
            header('Location: index.php?page=login&redirect=' . urlencode($page));
            exit;
        }

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

            case "article_download_pdf":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->downloadPdf();
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

            case "for_you":
                require_once self::CONTROLLERS_PATH . "ForYou_C.php";
                (new ForYouController())->index();
                break;

            case "morning":
                require_once self::CONTROLLERS_PATH . "Briefing_C.php";
                (new BriefingController())->morning();
                break;

            case "live":
                require_once self::CONTROLLERS_PATH . "Briefing_C.php";
                (new BriefingController())->live();
                break;

            case "ajax_live":
                require_once self::CONTROLLERS_PATH . "Briefing_C.php";
                (new BriefingController())->fetchLiveJson();
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

            case "check_username":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->ajax_check_username();
                break;

            case "check_email":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->ajax_check_email();
                break;

            case "forgot-password":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->forgotPassword();
                break;

            case "account":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->account();
                break;

            case "profile":
                include self::VIEWS_PATH . "User/Profile.php";
                break;

            case "logout":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->logout();
                break;

            // Admin Panel
            case "admin":
                require_once self::CONTROLLERS_PATH . "Admin_C.php";
                (new AdminController())->index();
                break;

            // Admin Articles
            case "admin_articles":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->admin_index();
                break;
            case "admin_article_create":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->create();
                break;
            case "admin_article_store":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->store();
                break;
            case "admin_article_edit":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->edit();
                break;
            case "admin_article_update":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->update();
                break;
            case "admin_article_delete":
                require_once self::CONTROLLERS_PATH . "Article_C.php";
                (new ArticleController())->destroy();
                break;

            // Admin Users
            case "admin_users":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->admin_index();
                break;
            case "admin_user_toggle":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->toggle_status();
                break;
            case "admin_user_promote":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->promote();
                break;
            case "admin_user_delete":
                require_once self::CONTROLLERS_PATH . "User_C.php";
                (new UserController())->destroy();
                break;

            // Admin Plans
            case "admin_plans":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->admin_plans();
                break;
            case "admin_plan_create":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->create_plan();
                break;
            case "admin_plan_store":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->store_plan();
                break;
            case "admin_plan_edit":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->edit_plan();
                break;
            case "admin_plan_update":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->update_plan();
                break;
            case "admin_plan_delete":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->destroy_plan();
                break;
            case "admin_subscription_assign":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->assign();
                break;
            case "admin_subscription_store_assignment":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->store_assignment();
                break;

            // Admin Comments
            case "admin_comments":
                require_once self::CONTROLLERS_PATH . "Comment_C.php";
                (new CommentController())->admin_index();
                break;
            case "admin_comment_approve":
                require_once self::CONTROLLERS_PATH . "Comment_C.php";
                (new CommentController())->approve();
                break;
            case "admin_comment_reject":
                require_once self::CONTROLLERS_PATH . "Comment_C.php";
                (new CommentController())->reject();
                break;
            case "admin_comment_delete":
                require_once self::CONTROLLERS_PATH . "Comment_C.php";
                (new CommentController())->destroy();
                break;

            // Games
            case "games":
                require_once self::CONTROLLERS_PATH . "Games_C.php";
                $controller = new GamesController();
                
                // Check if specific game action
                if (isset($_GET['action'])) {
                    if ($_GET['action'] === 'play' && isset($_GET['game'])) {
                        $controller->play($_GET['game']);
                    } elseif ($_GET['action'] === 'save') {
                        $controller->saveProgress();
                    } elseif ($_GET['action'] === 'reset') {
                        $controller->resetProgress();
                    } elseif ($_GET['action'] === 'load') {
                        $controller->getProgress();
                    } else {
                        $controller->index();
                    }
                } else {
                    $controller->index();
                }
                break;

            // Subscription
            case "subscribe":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->subscribe();
                break;
            case "cancel_subscription":
                require_once self::CONTROLLERS_PATH . "Subscription_C.php";
                (new SubscriptionController())->cancel();
                break;

            // Comments (user-facing)
            case "comment_store":
                require_once self::CONTROLLERS_PATH . "Comment_C.php";
                (new CommentController())->store();
                break;

            // Article Interactions (like/save)
            case "article_like":
                require_once self::CONTROLLERS_PATH . "ArticleInteraction_C.php";
                (new ArticleInteractionController())->like();
                break;

            case "article_save":
                require_once self::CONTROLLERS_PATH . "ArticleInteraction_C.php";
                (new ArticleInteractionController())->save();
                break;

            case "plans":
                include self::VIEWS_PATH . "Subscription/Plans.php";
                break;

            // 404
            default:
                http_response_code(404);
                echo "Page not found";
                break;
        }
    }
}

