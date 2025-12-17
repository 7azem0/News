<?php
require_once __DIR__ . "/../Config/DataBase_Connection.php";


class Article {

    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT id, title, thumbnail FROM articles WHERE status = 'published' ORDER BY created_at DESC");
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
            "SELECT id, title, content as description, thumbnail, COALESCE(scheduled_at, created_at) as publishedAt, author
             FROM articles
             WHERE status = 'published'
             ORDER BY is_featured DESC, COALESCE(scheduled_at, created_at) DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $query, ?string $category = null, $tags = null): array {
        $sql = "SELECT id, title, thumbnail, content as description, created_at 
                FROM articles 
                WHERE status = 'published' 
                  AND (scheduled_at IS NULL OR scheduled_at <= NOW())";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (title LIKE ? OR content LIKE ?)";
            $term = '%' . $query . '%';
            $params[] = $term;
            $params[] = $term;
        }

        if (!empty($category)) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }

        if (!empty($tags)) {
            // Support both string (single) and array (multiple)
            if (is_array($tags)) {
                $tagClauses = [];
                foreach ($tags as $t) {
                    $tagClauses[] = "tags LIKE ?";
                    $params[] = '%' . $t . '%';
                }
                if (!empty($tagClauses)) {
                    $sql .= " AND (" . implode(' OR ', $tagClauses) . ")";
                }
            } else {
                $sql .= " AND tags LIKE ?";
                $params[] = '%' . $tags . '%';
            }
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories(): array {
        // Standard categories that should always be available
        $standard = [
            'general', 'world', 'politics', 'technology', 'science', 'health', 
            'sports', 'entertainment', 'business', 'opinion', 'lifestyle', 'travel', 'culture'
        ];

        // Fetch used categories from DB
        $stmt = $this->conn->query("SELECT DISTINCT category FROM articles WHERE status = 'published' AND category IS NOT NULL AND category != ''");
        $dbCategories = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Merge, Unique, Sort
        $all = array_unique(array_merge($standard, $dbCategories));
        sort($all);

        return $all;
    }

    public function getAllTags(): array {
        // Fetch all tags strings, then explode and unique them in PHP
        $stmt = $this->conn->query("SELECT tags FROM articles WHERE status = 'published' AND tags IS NOT NULL AND tags != ''");
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $allTags = [];
        foreach ($rows as $row) {
            $tags = explode(',', $row);
            foreach ($tags as $t) {
                $t = trim($t);
                if (!empty($t)) {
                    $allTags[] = $t;
                }
            }
        }
        $allTags = array_unique($allTags);
        sort($allTags);
        return $allTags;
    }

    public function save(array $data): bool {
        // Enforce single featured article: if this one is being featured, unfeature all others first
        if (isset($data['is_featured']) && (int)$data['is_featured'] === 1) {
            $this->conn->exec("UPDATE articles SET is_featured = 0");
        }

        // If ID is present, update; otherwise insert
        if (!empty($data['id'])) {
            $stmt = $this->conn->prepare("
                UPDATE articles 
                SET title = :title, 
                    content = :content, 
                    author = :author, 
                    category = :category, 
                    thumbnail = :thumbnail,
                    status = :status,
                    scheduled_at = :scheduled_at,
                    is_featured = :is_featured,
                    visibility = :visibility,
                    tags = :tags,
                    required_plan_id = :required_plan_id
                WHERE id = :id
            ");
            return $stmt->execute([
                ':id'           => $data['id'],
                ':title'        => $data['title'] ?? '',
                ':content'      => $data['description'] ?? '',
                ':author'       => $data['author'] ?? 'Unknown',
                ':category'     => $data['category'] ?? 'general',
                ':thumbnail'    => $data['imageUrl'] ?? '',
                ':status'       => $data['status'] ?? 'draft',
                ':scheduled_at' => !empty($data['scheduled_at']) ? $data['scheduled_at'] : null,
                ':is_featured'  => isset($data['is_featured']) ? (int)$data['is_featured'] : 0,
                ':visibility'   => $data['visibility'] ?? 'public',
                ':tags'         => $data['tags'] ?? '',
                ':required_plan_id' => !empty($data['required_plan_id']) ? (int)$data['required_plan_id'] : null
            ]);
        } else {
            $stmt = $this->conn->prepare("
                INSERT INTO articles (title, content, author, category, thumbnail, language, status, scheduled_at, is_featured, visibility, tags, required_plan_id, created_at)
                VALUES (:title, :content, :author, :category, :thumbnail, :language, :status, :scheduled_at, :is_featured, :visibility, :tags, :required_plan_id, NOW())
            ");
            return $stmt->execute([
                ':title'        => $data['title'] ?? '',
                ':content'      => $data['description'] ?? '',
                ':author'       => $data['author'] ?? 'Unknown',
                ':category'     => $data['category'] ?? 'general',
                ':thumbnail'    => $data['imageUrl'] ?? '',
                ':language'     => 'en',
                ':status'       => $data['status'] ?? 'draft',
                ':scheduled_at' => !empty($data['scheduled_at']) ? $data['scheduled_at'] : null,
                ':is_featured'  => isset($data['is_featured']) ? (int)$data['is_featured'] : 0,
                ':visibility'   => $data['visibility'] ?? 'public',
                ':tags'         => $data['tags'] ?? '',
                ':required_plan_id' => !empty($data['required_plan_id']) ? (int)$data['required_plan_id'] : null
            ]);
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Admin: List all (drafts, etc)
    public function getAllAdmin(): array {
        $stmt = $this->conn->query("SELECT * FROM articles ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
