<?php
// Models/News.php

class News {

    public function fetch($country = 'us', $category = 'general'): array {
        $config = include __DIR__ . '/../config/API.php';
        $apiKey = $config['newsapi_key'];
        $apiUrl = $config['newsapi_url'];

        $url = $apiUrl . "?country={$country}&category={$category}&apiKey={$apiKey}";
        $response = @file_get_contents($url);
        if (!$response) return [];

        $data = json_decode($response, true);
        if (!isset($data['articles'])) return [];

        $articles = [];
        foreach ($data['articles'] as $item) {
            $articles[] = [
                'title' => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'url' => $item['url'] ?? '',
                'imageUrl' => $item['urlToImage'] ?? '',
                'publishedAt' => $item['publishedAt'] ?? ''
            ];
        }

        return $articles;
    }
}