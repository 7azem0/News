<?php
require_once "Models/Article.php";
require_once "Services/Translation_S.php";

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
        $availableLangs = $translator->availableLangs;

        $selectedLang = $_GET['lang'] ?? 'en';
        $displayArticle = $article;

        if ($selectedLang !== 'en') {
            try {
                $translated = $translator->translateArticle($id, $selectedLang);
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
