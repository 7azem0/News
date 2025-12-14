<?php

require_once __DIR__ . '/../Models/Article.php';
require_once __DIR__ . '/../Services/NewsAPI_S.php';

class SearchController {

    public function index(): void {
        $query = $_GET['q'] ?? '';

        if (empty($query)) {
            include __DIR__ . '/../Views/Articles/Search.php';
            return;
        }

        // Search articles in database
        $articleModel = new Article();
        $articles = $articleModel->search($query);

        // Search news from API
        $newsService = new NewsAPIService();
        $news = $newsService->search($query);

        include __DIR__ . '/../Views/Articles/Search.php';
    }
}
