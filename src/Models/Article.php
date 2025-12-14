<?php
require_once __DIR__ . "/../config/DataBase_Connection.php";


class Article {

    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT id, title, thumbnail FROM articles ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

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

    public function save(array $data): bool {
        $stmt = $this->conn->prepare("
            INSERT INTO articles (title, content, author, category, thumbnail, language, created_at)
            VALUES (:title, :content, :author, :category, :thumbnail, :language, NOW())
        ");
        return $stmt->execute([
            ':title'     => $data['title'] ?? '',
            ':content'   => $data['description'] ?? '',
            ':author'    => $data['author'] ?? 'Unknown',
            ':category'  => $data['category'] ?? 'general',
            ':thumbnail' => $data['imageUrl'] ?? '',
            ':language'  => 'en'
        ]);
    }

    // Fetch news from News API using environment variable
    public function fetchNews(string $country = 'us', string $category = 'general'): array {
        $apiKey = getenv('NEWS_API_KEY') ?: '';
        $apiUrl = 'https://newsapi.org/v2/top-headlines';

        if (empty($apiKey)) {
            return [];
        }

        $url = $apiUrl . "?country={$country}&category={$category}&apiKey={$apiKey}";
        $response = @file_get_contents($url);

        if ($response === false) {
            return [];
        }

        $data = json_decode($response, true);
        if (!isset($data['articles']) || !is_array($data['articles'])) {
            return [];
        }

        $articles = [];
        foreach ($data['articles'] as $item) {
            $articles[] = [
                'title'       => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'url'         => $item['url'] ?? '',
                'imageUrl'    => $item['urlToImage'] ?? '',
                'publishedAt' => $item['publishedAt'] ?? ''
            ];
        }

        return $articles;
    }
}
