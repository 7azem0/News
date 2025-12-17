<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/ArticleInteraction.php';

class ArticleInteractionController {

    public function like() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Not authenticated']);
            exit;
        }

        $articleId = $_POST['article_id'] ?? 0;
        
        if (empty($articleId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid article ID']);
            exit;
        }

        $model = new ArticleInteraction();
        $result = $model->toggleLike($_SESSION['user_id'], $articleId);
        
        echo json_encode([
            'success' => true,
            'action' => $result['action'],
            'count' => $result['count']
        ]);
    }

    public function save() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Not authenticated']);
            exit;
        }

        $articleId = $_POST['article_id'] ?? 0;
        
        if (empty($articleId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid article ID']);
            exit;
        }

        $model = new ArticleInteraction();
        $result = $model->toggleSave($_SESSION['user_id'], $articleId);
        
        echo json_encode([
            'success' => true,
            'action' => $result['action']
        ]);
    }
}
