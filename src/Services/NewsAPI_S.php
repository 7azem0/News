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

        if (empty($this->apiKey)) {
            error_log("NewsAPIService: API key is empty");
            return [];
        }

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
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response === false || !empty($err)) {
            return [];
        }

        $data = json_decode($response, true);
        if (!is_array($data) || !isset($data['articles'])) {
            return [];
        }

        return $data['articles'];
    }
}