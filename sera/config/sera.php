<?php

return [
    'illegal_sites' => [
        'booksearch.google.com',
        'books.google.com',
        'news.google.com',
        'blogsearch.google.com',
        'maps.google.com',
        'images.google.com',
    ],

    'google_data_centers' => [
        '64.233.179.104',
        '66.102.9.99',
        '66.102.9.147',
        '66.102.9.104',
        '64.233.187.99',
        '64.233.187.104',
        '64.233.183.99',
        '64.233.183.104',
        '64.233.179.99',
        '64.233.167.99',
        '64.233.167.147',
        '64.233.167.104',
        '64.233.161.99',
        '64.233.161.147',
        '64.233.161.104',
        '216.239.59.99',
        '216.239.59.147',
        '216.239.59.104',
        '216.239.59.103',
    ],

    'proxies' => array_filter(array_map('trim', explode(',', env('SERA_HTTP_PROXIES', '')))),

    'per_page' => 100,
    'listing' => 10,
    'max_fetch_attempts' => 50,
    'fetch_timeout' => 10,

    'mail_from' => env('SERA_MAIL_FROM', 'do-not-reply@example.com'),
    'mail_from_name' => env('SERA_MAIL_FROM_NAME', 'CM Sera Tool'),
];
