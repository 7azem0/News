<?php
session_start();
require_once __DIR__ . '/../Models/Article.php';
require_once __DIR__ . '/../Models/News.php';
require_once __DIR__ . '/../Services/Translation_S.php';
require_once __DIR__ . '/../Models/User.php';

class ArticleController {

    private $articleModel;
    private $newsModel;
    private $translator;
    private $userModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->newsModel = new News(); 
        $this->translator = new TranslationService();
        $this->userModel = new User();
    }

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

        $subscription = isset($_SESSION['user_id']) ? $this->userModel->getSubscription($_SESSION['user_id']) : null;
        $plan = $subscription['plan'] ?? null;
        
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

        include __DIR__ . '/../Views/Articles/Single.php';
    }

    public function news($country = 'us', $category = 'technology') {
        $articles = $this->newsModel->fetch($country, $category);
        include __DIR__ . '/../Views/Articles/news.php';
    }
}
