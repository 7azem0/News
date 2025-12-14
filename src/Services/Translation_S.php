<?php

class TranslationService {
    private $conn;
    private $apiUrl;
    private $apiKey;

    private $allLangs = [
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

    public $availableLangs = [];

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();

        // Allow overriding the translation API endpoint and key via env vars.
        // Defaults to the public LibreTranslate instance (no key required).
        $this->apiUrl = getenv('LIBRE_TRANSLATE_URL') ?: 'https://libretranslate.com/translate';
    }


    public function translateText(string $text, string $targetLang, string $sourceLang = 'auto', string $plan = null) {
        // basic validation
        if (trim($text) === '') return '';
        // Allow all languages for translation, plan restrictions are handled at higher level
        if (!isset($this->allLangs[$targetLang])) {
            throw new Exception("Unsupported target language: $targetLang");
        }

        $postData = [
            'q' => $text,
            'source' => $sourceLang,
            'target' => $targetLang,
            'format' => 'text'
        ];

        // Optional API key support (for self-hosted LibreTranslate or compatible APIs)
        if (!empty($this->apiKey)) {
            $postData['api_key'] = $this->apiKey;
        }

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        // timeouts
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) throw new Exception("Error in cURL: $err");
        if ($httpCode >= 400) throw new Exception("Translation API returned HTTP code $httpCode");

        $data = json_decode($resp, true);
        if ($data === null) throw new Exception("Invalid JSON response from translation API");
        if (isset($data['error'])) {
            throw new Exception("API Error: " . $data['error']);
        }

        // LibreTranslate uses 'translatedText'
        return $data['translatedText'] ?? $data['translated_text'] ?? null;
    }

    public function getAvailableLangsForPlan(string $plan = null): array {
        if ($plan === 'Plus') {
            return [
                'ar' => 'Arabic',
                'fr'=>'French',
                'es'=>'Spanish',
                'de'=>'German',
                'zh'=>'Chinese (Mandarin)',
                'ja'=>'Japanese',
                'ko'=>'Korean',
                'en' => 'English'
            ];
        } elseif ($plan === 'Basic') {
            return [
                'ar' => 'Arabic',
                'en' => 'English'
            ];
        } elseif ($plan === 'Pro') {
            return $this->allLangs; // No translation access for Pro plan
        } else {
            // For other plans or no plan, return empty
            return [];
        }
    }

    public function translateArticle(int $articleId, string $targetLang, string $plan = null): array {
        // validate target language based on plan
        $allowedLangs = $this->getAvailableLangsForPlan($plan);
        if (!isset($allowedLangs[$targetLang])) {
            throw new Exception("Unsupported target language: $targetLang for plan: $plan");
        }

        // Check cache (matches your translations table: article_id, language, translated_text)
        $stmt = $this->conn->prepare("SELECT translated_text FROM translations WHERE article_id = ? AND language = ?");
        $stmt->execute([$articleId, $targetLang]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['translated_text'])) {
            $data = json_decode($row['translated_text'], true);
            if (is_array($data) && isset($data['title']) && isset($data['content'])) {
                return $data;
            }
            // Fallback: if translated_text was plain content, return it as content
            return ['title' => null, 'content' => $row['translated_text']];
        }

        // Fetch original article
        $stmt = $this->conn->prepare("SELECT title, content FROM articles WHERE id = ?");
        $stmt->execute([$articleId]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article) throw new Exception("Article not found");

        // Translate pieces
        $translatedTitle = $this->translateText($article['title'], $targetLang);
        $translatedContent = $this->translateText($article['content'], $targetLang);

        // Save as JSON in translated_text
        $payload = json_encode(['title' => $translatedTitle, 'content' => $translatedContent], JSON_UNESCAPED_UNICODE);

        $stmt = $this->conn->prepare(
            "INSERT INTO translations (article_id, language, translated_text)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE translated_text = VALUES(translated_text)"
        );
        $stmt->execute([$articleId, $targetLang, $payload]);

        return ['title' => $translatedTitle, 'content' => $translatedContent];
    }
}