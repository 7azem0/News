<?php
session_start();
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
        $this->translator = new TranslationService();
        $this->userModel = new User();
        $this->newsService = new NewsAPIService();
    }

    public function news(string $country = 'us', string $category = 'technology') {
        $articles = $this->newsService->fetch($country, $category);

        if (empty($articles)) {
            $articles = $this->articleModel->fetchNews($country, $category);
        }

        include VIEWS_PATH . 'news.php';
    }
}