<?php
// src/Services/NewsFetcher.php

class NewsFetcher {

    private string $apiKey;
    private string $apiUrl;

    public function __construct() {
        $config = include __DIR__ . '/../config/API.php';
        $this->apiKey = $config['newsapi_key'];
        $this->apiUrl = $config['newsapi_url'];
    }

    public function fetch(
        string $country = 'us',
        string $category = 'general'
    ): array {

        $url = sprintf(
            "%s?country=%s&category=%s&apiKey=%s",
            $this->apiUrl,
            $country,
            $category,
            $this->apiKey
        );

        $response = @file_get_contents($url);
        if (!$response) return [];

        $data = json_decode($response, true);
        if (empty($data['articles'])) return [];

        return array_map(function ($item) {
            return [
                'title'       => $item['title'] ?? '',
                'description' => $item['description'] ?? '',
                'author'      => $item['author'] ?? '',
                'imageUrl'    => $item['urlToImage'] ?? '',
                'publishedAt'=> $item['publishedAt'] ?? ''
            ];
        }, $data['articles']);
    }
}