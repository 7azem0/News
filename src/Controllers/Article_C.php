<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/Article.php';
require_once __DIR__ . '/../Services/NewsAPI_S.php';
require_once __DIR__ . '/../Services/Translation_S.php';
require_once __DIR__ . '/../Models/User.php';

define('VIEWS_PATH', __DIR__ . '/../Views/Articles/');

class ArticleController {

    private Article $articleModel;
    private TranslationService $translator;
    private User $userModel;
    private NewsAPIService $newsService;

    public function __construct() {
        $this->articleModel = new Article();
        $this->translator   = new TranslationService();
        $this->userModel    = new User();
        $this->newsService  = new NewsAPIService();
    }

    /**
     * News page (external API with DB fallback)
     */
    public function news(string $country = 'us', string $category = 'technology') {
        $articles = $this->newsService->fetch($country, $category);

        // If API fails or returns nothing, fallback to DB
        if (empty($articles)) {
            $articles = $this->articleModel->fetchNews($country, $category);
        }

        include VIEWS_PATH . 'news.php';
    }

    /**
     * Articles list OR single article with translation
     */
    public function index() {
        // Articles listing
        if (!isset($_GET['id'])) {
            $articles = $this->articleModel->getAll();
            $news = $this->newsService->fetch('us', 'technology');

            $selectedLang = $_GET['lang'] ?? 'en';

            // Get user subscription if logged in
            $subscription = null;
            if (isset($_SESSION['user_id'])) {
                $subscription = $this->userModel->getSubscription($_SESSION['user_id']);
            }

            $plan = $subscription['plan'] ?? null;

            // Get available languages for plan
            $availableLangs = $this->translator->getAvailableLangsForPlan($plan);

            // Always include English
            if (!isset($availableLangs['en'])) {
                $availableLangs = ['en' => 'English'] + $availableLangs;
            }

            // Translation logic
            if ($selectedLang !== 'en') {
                try {
                    // Translate articles
                    foreach ($articles as &$article) {
                        $translated = $this->translator->translateArticle($article['id'], $selectedLang, $plan);
                        $article['title'] = $translated['title'] ?? $article['title'];
                        $article['content'] = $translated['content'] ?? $article['content'];
                    }

                    // Translate news
                    foreach ($news as &$item) {
                        $item['title'] = $this->translator->translateText($item['title'], $selectedLang);
                        $item['description'] = $this->translator->translateText($item['description'], $selectedLang);
                    }
                } catch (Exception $e) {
                    // fallback to original
                }
            }

            include VIEWS_PATH . 'list.php';
            return;
        }

        // Single article
        $id = (int) $_GET['id'];
        $article = $this->articleModel->getById($id);

        if (!$article) {
            http_response_code(404);
            echo "Article not found";
            return;
        }

        // Get user subscription if logged in
        $subscription = null;
        if (isset($_SESSION['user_id'])) {
            $subscription = $this->userModel->getSubscription($_SESSION['user_id']);
        }

        $plan = $subscription['plan'] ?? null;

        // Get available languages for plan
        $availableLangs = $this->translator->getAvailableLangsForPlan($plan);

        // Always include English
        if (!isset($availableLangs['en'])) {
            $availableLangs = ['en' => 'English'] + $availableLangs;
        }

        $selectedLang  = $_GET['lang'] ?? 'en';
        $displayArticle = $article;

        // Translation logic
        if ($selectedLang !== 'en') {
            try {
                $translated = $this->translator->translateArticle($id, $selectedLang, $plan);
                $displayArticle['title']   = $translated['title'] ?? $article['title'];
                $displayArticle['content'] = $translated['content'] ?? $article['content'];
            } catch (Exception $e) {
                // fallback to original
                $displayArticle['title']   = $article['title'];
                $displayArticle['content'] = $article['content'];
            }
        }

        include VIEWS_PATH . 'Single.php';
    }
}
