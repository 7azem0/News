<?php
require_once __DIR__ . "/../Models/Article.php";
require_once __DIR__ . "/../Models/User.php";

class ForYouController {
    private Article $articleModel;
    private User $userModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->userModel = new User();
    }

    /**
     * Display the For You page with recommendations and trendy articles
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        
        // Fetch recommendations (requires login, otherwise defaults to latest)
        $recommendations = [];
        if ($userId) {
            $recommendations = $this->articleModel->getRecommendations($userId, 6);
        } else {
            $recommendations = $this->articleModel->getLatest(6);
        }

        // Fetch trendy articles (based on like counts)
        $trendyArticles = $this->articleModel->getTrendyArticles(6);

        // Pass data to view
        include __DIR__ . "/../Views/Articles/ForYou.php";
    }
}
