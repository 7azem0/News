<?php
require_once __DIR__ . "/../Models/Article.php";
require_once __DIR__ . "/../Services/NewsAPI_S.php";

class BriefingController {
    private Article $articleModel;
    private NewsAPIService $newsService;

    public function __construct() {
        $this->articleModel = new Article();
        $this->newsService = new NewsAPIService();
    }

    /**
     * The Morning: A curated daily guide.
     */
    public function morning() {
        // 1. Get Top Local Featured Articles
        $featured = $this->articleModel->getFeatured(3);
        
        // 2. Get Top Global Headlines - Limited to 5
        $globalHeadlines = $this->newsService->getTopHeadlines('general', 'us', 5);

        include __DIR__ . "/../Views/Briefing/Morning.php";
    }

    /**
     * Live Briefings: Real-time breaking news updates.
     */
    public function live() {
        // Get absolute latest breaking news - Limited to 7
        $liveNews = $this->newsService->getBreakingNews(7);

        include __DIR__ . "/../Views/Briefing/LiveFeed.php";
    }

    /**
     * JSON Endpoint for AJAX updates.
     */
    public function fetchLiveJson() {
        header('Content-Type: application/json');
        try {
            $liveNews = $this->newsService->getBreakingNews(7);
            echo json_encode(['success' => true, 'news' => $liveNews]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
