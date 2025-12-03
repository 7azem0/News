<?php
require_once "config/DataBase_Connection.php";

class Article {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT id, title, thumbnail FROM articles ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get latest articles limited by count.
     *
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getLatest(int $limit = 5): array {
        $stmt = $this->conn->prepare(
            "SELECT id, title, thumbnail
             FROM articles
             ORDER BY created_at DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search articles by title or content.
     *
     * @param string $query
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query): array {
        $term = '%' . $query . '%';

        $stmt = $this->conn->prepare(
            "SELECT id, title, thumbnail
             FROM articles
             WHERE title LIKE ? OR content LIKE ?
             ORDER BY created_at DESC"
        );

        $stmt->execute([$term, $term]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
