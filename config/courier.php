<?php

return
    [
        'pathao_merchant' => [
            'client_id' => 'pmbkRMrazJ',
            'client_secret' => '0LSKYVeihZb5EILFdWh6OLwdjm1Dg57LYWpQ7R4r',
            'username' => 'riazuliqbalferoz@gmail.com',
            'password' => 'xkfgw4n65',
            'grant_type' => 'password',
            'webhook_secret' => '0LSKYVeihZb5EILFdWh6OLwdjm1Dg57LYWpQ7R4r',
        ],
        'steadfast_merchant' => [
            'ApiKey' => 'pzz7dyrowzbj2xtkoobkoxvcr9w2isri',
            'SecretKey' => 'bjllqbcsnysmokxzqagsodm5',
        ],
        'redx_merchant' => [
            'api_url' => 'https://openapi.redx.com.bd/v1.0.0-beta',
            'api_access_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI4ODg3MTciLCJpYXQiOjE2ODExMTg3ODUsImlzcyI6InVHMkhMM0lVcWdmbUc2dnl3VkxOQTdYTVp1NHhEeGhhIiwic2hvcF9pZCI6ODg4NzE3LCJ1c2VyX2lkIjo4MDA4MDY4fQ.qp1iBrdUw0NCKiGVxSqhxZ2biNqZWa18or4O-3e0aL0',
        ],
        'bahok_merchant' => [
            'base_url' => env('BAHOK_BASE_URL', 'https://api.bahokcourier.com'),
            'client_id' => env('BAHOK_CLIENT_ID', 'bK8mepphokeMy'),
            'client_secret' => env('BAHOK_CLIENT_SECRET', 'ZK21kv1S1yhHD5hjs1u7ltIjkXPFDPFTIonmRF7s'),
            'username' => env('BAHOK_USERNAME', ''),
            'password' => env('BAHOK_PASSWORD', ''),
        ],
    ];
