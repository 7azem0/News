<?php
require_once __DIR__ . "/../config/DataBase_Connection.php";

class Comment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->connect();
    }

    public function getAllComments() {
        // Fetch comments with user and article details
        $sql = "SELECT c.*, u.username, a.title as article_title 
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN articles a ON c.article_id = a.id
                ORDER BY c.created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE comments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // For user facing part (future)
    public function getByArticleId($articleId) {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.username 
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.article_id = ? AND c.status = 'approved'
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($userId, $articleId, $content) {
        $stmt = $this->conn->prepare("INSERT INTO comments (user_id, article_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $articleId, $content]);
    }
}
