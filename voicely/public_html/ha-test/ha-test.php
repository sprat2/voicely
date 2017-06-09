<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set up HybridAuth
session_start();


include "$_SERVER[DOCUMENT_ROOT]/wp-content/plugins/voicely-core/includes/vendor/autoload.php";


$config = [
    'callback' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", // or Hybridauth\HttpClient\Util::getCurrentUrl()
    'keys' => ["key" => "c4L94qXTR3hwCI1WUtGGAaBeF", "secret" => "RH1D8O3qazcveJMpDZfcm8CLQ7TJv24ReTi4FOCS3Z7snFqwri" ],
];

try {
    $github = new Hybridauth\Provider\Twitter($config);
    $github->authenticate();
    $userProfile = $github->getUserProfile();
    echo 'Hi '.$userProfile->displayName;
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage();
}
