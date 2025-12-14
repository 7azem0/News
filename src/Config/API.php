<?php
$config = include __DIR__ . '/../config/API.php';
$apiKey = $config['newsapi_key'];
$apiUrl = $config['newsapi_url'];

return [
    'newsapi_key' => 'e519a661788549ae8cea66aa8c762724',
    'newsapi_url' => 'https://newsapi.org/v2/top-headlines'
];
