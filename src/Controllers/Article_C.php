<?php
require_once "Models/Article.php";
require_once "Services/Translation_S.php";
require_once "Models/User.php";

class ArticleController {

    public function index() {
        $articleModel = new Article();

        // Articles listing
        if (!isset($_GET['id'])) {
            $articles = $articleModel->getAll();
            include "Views/Articles/list.php";
            return;
        }

        // Single article with optional translation
        $id = (int) $_GET['id'];
        $article = $articleModel->getById($id);

        if (!$article) {
            http_response_code(404);
            echo "Article not found";
            return;
        }

        $translator = new TranslationService();

        // Get user subscription to filter available languages
        $userModel = new User();
        $subscription = null;
        if (isset($_SESSION['user_id'])) {
            $subscription = $userModel->getSubscription($_SESSION['user_id']);
        }
        $plan = $subscription ? $subscription['plan'] : null;
        $availableLangs = $translator->getAvailableLangsForPlan($plan);

        // Always include English as the default option
        if (!isset($availableLangs['en'])) {
            $availableLangs = ['en' => 'English'] + $availableLangs;
        }

        $selectedLang = $_GET['lang'] ?? 'en';
        $displayArticle = $article;
        $currentPlan = $plan; // Pass plan to view for debugging

        if ($selectedLang !== 'en') {
            try {
                $translated = $translator->translateArticle($id, $selectedLang, $plan);
                // Keep non-text fields (e.g., author, thumbnail) from original article
                $displayArticle['title'] = $translated['title'] ?? $article['title'];
                $displayArticle['content'] = $translated['content'] ?? $article['content'];
            } catch (Exception $e) {
                // On error fall back to original article content
                $displayArticle['title'] = $article['title'];
                $displayArticle['content'] = $article['content'];
            }
        }

        include "Views/Articles/Signle.php";
    }
}
