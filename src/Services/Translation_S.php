<?php
require_once "config/db.php";

class TranslationService {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function translate($article_id, $target_lang) {
        // Check cache
        $stmt = $this->conn->prepare("SELECT translated_text FROM translations WHERE article_id=? AND language=?");
        $stmt->execute([$article_id, $target_lang]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) return $row['translated_text'];

        // If not cached, use free API or mock
        $stmt = $this->conn->prepare("SELECT content FROM articles WHERE id=?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        // Mock translation for demo (replace with real API)
        $translated = "[{$target_lang} translation] " . $article['content'];

        // Save to DB
        $stmt = $this->conn->prepare(
            "INSERT INTO translations(article_id, language, translated_text) VALUES (?, ?, ?)"
        );
        $stmt->execute([$article_id, $target_lang, $translated]);

        return $translated;
    }
}
