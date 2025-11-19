<?php
require_once "models/Article.php";

class ArticleController {

    public function index() {
        $articleModel = new Article();
        
        if (!isset($_GET['id'])) {
            $articles = $articleModel->getAll();
            include "views/articles/list.php";
        } else {
            $article = $articleModel->getById($_GET['id']);
            include "views/articles/single.php";
        }
    }
}
