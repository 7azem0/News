<?php
require_once "config/db.php";

class TranslationService {
    private $conn;
    private $apiUrl = "https://libretranslate.de/translate";

    public $availableLangs = [
        'ar'=>'Arabic',
        'en'=>'English',
        'fr'=>'French',
        'es'=>'Spanish',
        'de'=>'German',
        'zh'=>'Chinese (Mandarin)',
        'ja'=>'Japanese',
        'ko'=>'Korean',
        'ru'=>'Russian',
        'it'=>'Italian',
        'pt'=>'Portuguese',
        'hi'=>'Hindi',
        'tr'=>'Turkish',
        'nl'=>'Dutch',
        'sv'=>'Swedish',
        'fa'=>'Persian'
    ];

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /* public function translate($article_id, $target_lang) {
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
    } */



    public function translateText(string $text,string $targetLang, string $sourceLang = 'auto') {
        $postData = [
            'q' => $text,
            'source' => $sourceLang,
            'target' => $targetLang,
            'format' => 'text'
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        $resp = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err) throw new Exception("Error in cURL: $err");

        $data = json_decode($resp, true);
        if(isset($data['error'])) {
            throw new Exception("API Error: " . $data['error']);
        }

        return $data['translatedText'] ?? null;
    }

    public function translateArticle(int $articleId, string $targetLang): array{
        // Fetch article
        $stmt = $this->conn->prepare("SELECT title_translated, content_translated FROM translations WHERE article_id=? AND target_lang=?");
        $stmt->execute([$articleId, $targetLang]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) return $row;
        
        $stmt = $this->conn->prepare("SELECT title, content FROM articles WHERE id=?");
        $stmt->execute([$articleId]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$article) throw new Exception("Article not found");

        $translatedTitle = $this->translateText($article['title'], $targetLang);
        $translatedContent = $this->translateText($article['content'], $targetLang);
        
        // Save translation
        $stmt = $this->conn->prepare(
            "INSERT INTO translations (article_id, target_lang, title_translated, content_translated, provider)
             VALUES (:aid, :lang, :title, :content, 'libretranslate')
             ON DUPLICATE KEY UPDATE 
                title_translated = VALUES(title_translated),
                content_translated = VALUES(content_translated)"
        );
        $stmt->execute([$articleId, $targetLang, $translatedTitle, $translatedContent]);
        return [
            'title'=>$translatedTitle, 
            'content'=>$translatedContent
        ];
    }
}
