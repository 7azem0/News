<?php

class NewsAPIService {

    private string $apiKey;
    private string $apiUrl;

    public function __construct() {
        $this->apiKey = getenv('NEWS_API_KEY') ?: 'e519a661788549ae8cea66aa8c762724';
        $this->apiUrl = 'https://newsapi.org/v2/top-headlines';
    }

    /**
     * Fetch news from the News API.
     *
     * @param string $country
     * @param string $category
     * @param string $keyword
     * @return array<int, array<string, mixed>>
     */
    public function fetch(string $country = 'us', string $category = 'general', string $keyword = ''): array {
    $params = array_filter([
        'country'  => $country,
        'category' => $category,
        'q'        => $keyword,
        'apiKey'   => $this->apiKey,
        'pageSize' => 30
    ]);

    $url = $this->apiUrl . '?' . http_build_query($params);

   $ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => [
        "User-Agent: NewsApp/1.0",  // <--- Add this line
    ],
]);


    $response = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || !empty($err)) {
        error_log("NewsAPI cURL error: $err");
        return [];
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        error_log("NewsAPI invalid JSON: " . $response);
        return [];
    }

    if (($data['status'] ?? '') !== 'ok') {
        error_log("NewsAPI returned error: " . json_encode($data));
        return [];
    }

    return $data['articles'];
}

    /**
     * Search news using the everything endpoint.
     *
     * @param string $query
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query, ?string $category = null): array {
        // Build query with category if present
        if (!empty($category)) {
            $query = trim($query . ' ' . $category);
        }

        // If query is still empty (no keyword, no category), return empty
        if (empty($query)) {
            return [];
        }

        $params = [
            'q'        => $query,
            'apiKey'   => $this->apiKey,
            'pageSize' => 20,
            'sortBy'   => 'publishedAt'
        ];

        $url = 'https://newsapi.org/v2/everything?' . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                "User-Agent: NewsApp/1.0",
            ],
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || !empty($err)) {
            error_log("NewsAPI search cURL error: $err");
            return [];
        }

        $data = json_decode($response, true);

        if (!is_array($data)) {
            error_log("NewsAPI search invalid JSON: " . $response);
            return [];
        }

        if (($data['status'] ?? '') !== 'ok') {
            error_log("NewsAPI search returned error: " . json_encode($data));
            return [];
        }

        return $data['articles'] ?? [];
    }

}
