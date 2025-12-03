<?php
require_once "Models/Article.php";

class SearchController {

    public function index() {
        $query = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

        $articles = [];
        $error = null;

        if ($query !== '') {
            try {
                $articleModel = new Article();
                $articles = $articleModel->search($query);
            } catch (Exception $e) {
                // Keep error generic for users; log separately if needed
                $error = "Search failed. Please try again later.";
            }
        }

        // $query and $articles are consumed by the view
        include "Views/Articles/Search.php";
    }
}