<?php

require_once __DIR__ . '/../Models/Article.php';
require_once __DIR__ . '/../Services/NewsAPI_S.php';

class SearchController {

    public function index(): void {
        $query = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? null;
        $author = $_GET['author'] ?? null;
        $language = $_GET['language'] ?? null;
        
        // Support 'tags' array or fallback to single 'tag'
        $tags = $_GET['tags'] ?? ($_GET['tag'] ?? null);

        // If no query and no filters, just show empty page
        if (empty($query) && empty($category) && empty($tags) && empty($author) && empty($language)) {
            $articleModel = new Article(); // Need this for filters below
            $allCategories = $articleModel->getAllCategories();
            $allTags = $articleModel->getAllTags();
            $allAuthors = $articleModel->getAllAuthors();
            $allLanguages = $articleModel->getAllLanguages();
            include __DIR__ . '/../Views/Articles/Search.php';
            return;
        }

        $articleModel = new Article();
        
        // Fetch filters for Sidebar
        $allCategories = $articleModel->getAllCategories();
        $allTags = $articleModel->getAllTags();
        $allAuthors = $articleModel->getAllAuthors();
        $allLanguages = $articleModel->getAllLanguages();

        // Search articles in database with filters
        $articles = $articleModel->search($query, $category, $tags, $author, $language);

        // Search news from API
        // Only search if we have a query or a category (API doesn't support tags really well in this context so we ignore tags for API)
        $news = [];
        if (!empty($query) || !empty($category)) {
            $newsService = new NewsAPIService();
            $news = $newsService->search($query, $category, $language);
        }

        include __DIR__ . '/../Views/Articles/Search.php';
    }
}
