<?php
require_once __DIR__ . "/../Config/DataBase_Connection.php";

class ArticleInteraction {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    // ===== LIKES =====
    
    public function toggleLike($userId, $articleId) {
        // Check if already liked
        if ($this->isLiked($userId, $articleId)) {
            // Unlike
            $stmt = $this->conn->prepare("DELETE FROM article_likes WHERE user_id = ? AND article_id = ?");
            $stmt->execute([$userId, $articleId]);
            return ['action' => 'unliked', 'count' => $this->getLikeCount($articleId)];
        } else {
            // Like
            $stmt = $this->conn->prepare("INSERT INTO article_likes (user_id, article_id) VALUES (?, ?)");
            $stmt->execute([$userId, $articleId]);
            return ['action' => 'liked', 'count' => $this->getLikeCount($articleId)];
        }
    }

    public function isLiked($userId, $articleId) {
        $stmt = $this->conn->prepare("SELECT id FROM article_likes WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$userId, $articleId]);
        return $stmt->fetch() !== false;
    }

    public function getLikeCount($articleId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM article_likes WHERE article_id = ?");
        $stmt->execute([$articleId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getLikedArticles($userId) {
        $stmt = $this->conn->prepare("
            SELECT a.*, al.created_at as liked_at
            FROM articles a
            INNER JOIN article_likes al ON a.id = al.article_id
            WHERE al.user_id = ?
            ORDER BY al.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===== SAVES =====
    
    public function toggleSave($userId, $articleId) {
        // Check if already saved
        if ($this->isSaved($userId, $articleId)) {
            // Unsave
            $stmt = $this->conn->prepare("DELETE FROM saved_articles WHERE user_id = ? AND article_id = ?");
            $stmt->execute([$userId, $articleId]);
            return ['action' => 'unsaved'];
        } else {
            // Save
            $stmt = $this->conn->prepare("INSERT INTO saved_articles (user_id, article_id) VALUES (?, ?)");
            $stmt->execute([$userId, $articleId]);
            return ['action' => 'saved'];
        }
    }

    public function isSaved($userId, $articleId) {
        $stmt = $this->conn->prepare("SELECT id FROM saved_articles WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$userId, $articleId]);
        return $stmt->fetch() !== false;
    }

    public function getSavedArticles($userId) {
        $stmt = $this->conn->prepare("
            SELECT a.*, sa.saved_at
            FROM articles a
            INNER JOIN saved_articles sa ON a.id = sa.article_id
            WHERE sa.user_id = ?
            ORDER BY sa.saved_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
