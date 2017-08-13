<?php
// Hybridauth configuration array
$config = [
    // Location where to redirect users once they authenticate with a provider
    'callback' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),

    // Providers
    'providers' => [
        'Twitter'  => [
            'enabled' => true,
            'keys' => [ 'key' => 'c4L94qXTR3hwCI1WUtGGAaBeF', 'secret' => 'RH1D8O3qazcveJMpDZfcm8CLQ7TJv24ReTi4FOCS3Z7snFqwri']],
        'Google'   => [
            'enabled' => true,
            'keys' => [ 'id'  => '822285507867-779tnut9hd0bpvkk54oikgk9276tsb0q.apps.googleusercontent.com', 'secret' => '4subJgKfmnRHaZGWRr6cbAJ6'],
            'scope' => 'profile https://www.google.com/m8/feeds https://www.googleapis.com/auth/contacts.readonly',
        ],
        'Facebook' => [
            'enabled' => true,
            // 'scope'   => ['email', 'user_about_me', 'user_birthday', 'user_hometown']
            'keys' => [ 'id'  => '132906143984825', 'secret' => '0a6b2eff6b61e0942f2c4e3371d0881d'],
            'display' => 'popup',
        ],
        'Instagram' => ['enabled' => true, 'keys' => [ 'key' => '5adbfb407cd541c3bb199da69b029eb9', 'secret' => 'a24e2b50ceec4299b44fb0eca24c2eec' ]],
        'WindowsLive' => ['enabled' => true, 'keys' => [ 'key' => '46b8f88e-c25b-4c8c-ba31-28ea13834ddc', 'secret' => 'ozxp6roxxpzy8YjnXFJbt5e' ]],
    ]
];