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

    public function search($query = '', $category = null, $tags = null, $author = null, $language = null): array {
        $sql = "SELECT * FROM articles WHERE status = 'published'";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (title LIKE ? OR content LIKE ?)";
            $params[] = '%' . $query . '%';
            $params[] = '%' . $query . '%';
        }

        if (!empty($category)) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }

        if (!empty($tags)) {
            if (is_array($tags)) {
                $placeholders = implode(' OR ', array_fill(0, count($tags), 'tags LIKE ?'));
                $sql .= " AND ($placeholders)";
                foreach ($tags as $tag) {
                    $params[] = '%' . $tag . '%';
                }
            } else {
                $sql .= " AND tags LIKE ?";
                $params[] = '%' . $tags . '%';
            }
        }

        if (!empty($author)) {
            $sql .= " AND author = ?";
            $params[] = $author;
        }

        if (!empty($language)) {
            $sql .= " AND language = ?";
            $params[] = $language;
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

    public function getAllAuthors(): array {
        $stmt = $this->conn->query("SELECT DISTINCT author FROM articles WHERE status = 'published' AND author IS NOT NULL AND author != '' ORDER BY author ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllLanguages(): array {
        // Fetch used languages from DB
        $stmt = $this->conn->query("SELECT DISTINCT language FROM articles WHERE status = 'published' AND language IS NOT NULL AND language != ''");
        $dbLangs = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Standard set to ensure common ones appear cleanly
        $standard = ['English', 'Arabic', 'French', 'Spanish', 'German', 'Chinese', 'Japanese', 'Russian', 'Italian', 'Portuguese'];
        
        // Merge and Normalize
        $all = array_merge($standard, $dbLangs);
        $normalized = [];
        foreach ($all as $lang) {
            $lang = trim($lang);
            if (empty($lang)) continue;
            
            // Map common codes to full names
            if (strtolower($lang) === 'en') $lang = 'English';
            if (strtolower($lang) === 'ar') $lang = 'Arabic';
            if (strtolower($lang) === 'fr') $lang = 'French';
            if (strtolower($lang) === 'es') $lang = 'Spanish';
            if (strtolower($lang) === 'de') $lang = 'German';
            
            $normalized[] = $lang;
        }
        
        $unique = array_unique($normalized);
        sort($unique);
        
        return $unique;
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
                    language = :language,
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
                ':language'     => $data['language'] ?? 'English',
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
                ':language'     => $data['language'] ?? 'English',
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

    /**
     * Get recommended articles for a user based on categories and tags of articles they liked.
     */
    public function getRecommendations(int $userId, int $limit = 6): array {
        // 1. Get categories and tags from liked articles
        $stmt = $this->conn->prepare("
            SELECT DISTINCT a.category, a.tags 
            FROM article_likes l
            JOIN articles a ON l.article_id = a.id
            WHERE l.user_id = ?
        ");
        $stmt->execute([$userId]);
        $likedData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($likedData)) {
            // Fallback to latest articles if no likes found
            return $this->getLatest($limit);
        }

        $categories = [];
        $tags = [];
        foreach ($likedData as $row) {
            if (!empty($row['category'])) $categories[] = $row['category'];
            if (!empty($row['tags'])) {
                $tList = explode(',', $row['tags']);
                foreach ($tList as $t) {
                    $t = trim($t);
                    if (!empty($t)) $tags[] = $t;
                }
            }
        }
        $categories = array_unique($categories);
        $tags = array_unique($tags);

        // 2. Query for recommended articles
        $sql = "SELECT DISTINCT a.* FROM articles a 
                WHERE a.status = 'published' 
                AND a.id NOT IN (SELECT article_id FROM article_likes WHERE user_id = ?)";
        $params = [$userId];

        $conditions = [];
        if (!empty($categories)) {
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $conditions[] = "a.category IN ($placeholders)";
            foreach ($categories as $cat) $params[] = $cat;
        }

        if (!empty($tags)) {
            $tagConditions = [];
            foreach ($tags as $tag) {
                $tagConditions[] = "a.tags LIKE ?";
                $params[] = '%' . $tag . '%';
            }
            if (!empty($tagConditions)) {
                $conditions[] = "(" . implode(' OR ', $tagConditions) . ")";
            }
        }

        if (!empty($conditions)) {
            $sql .= " AND (" . implode(' OR ', $conditions) . ")";
        }

        $sql .= " ORDER BY a.created_at DESC LIMIT ?";
        $params[] = $limit;

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $i => $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($i + 1, $val, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get trendy articles based on like count.
     */
    public function getTrendyArticles(int $limit = 6): array {
        $stmt = $this->conn->prepare("
            SELECT a.*, COUNT(l.id) as like_count
            FROM articles a
            LEFT JOIN article_likes l ON a.id = l.article_id
            WHERE a.status = 'published'
            GROUP BY a.id
            HAVING like_count > 0
            ORDER BY like_count DESC, a.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
