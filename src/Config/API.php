<?php

return [
    'newsapi_key' => getenv('NEWSAPI_KEY') ?: 'e519a661788549ae8cea66aa8c762724',
    'newsapi_url' => getenv('NEWSAPI_URL') ?: 'https://newsapi.org/v2/top-headlines',
    'default_country' => 'us',
    'default_category' => 'general',
];
