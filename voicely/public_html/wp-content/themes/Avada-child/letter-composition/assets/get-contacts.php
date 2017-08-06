<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Load WordPress functionality
//   (required for nonce verification - may lighten later by only importing required functionality)
define('WP_USE_THEMES', false);
require('../../../../../wp-load.php');

// Set up Hybridauth
// Import Hybridauth
include '../vendor/autoload.php';
use Hybridauth\Hybridauth; 
// Hybridauth configuration array
$config = [
    // Location where to redirect users once they authenticate with a provider
    'callback' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),

    // Providers
    'providers' => [
        'Twitter'  => ['enabled' => true, 'keys' => [ 'key' => 'c4L94qXTR3hwCI1WUtGGAaBeF', 'secret' => 'RH1D8O3qazcveJMpDZfcm8CLQ7TJv24ReTi4FOCS3Z7snFqwri']],
        'Google'   => ['enabled' => true,'keys' => [ 'id'  => '822285507867-779tnut9hd0bpvkk54oikgk9276tsb0q.apps.googleusercontent.com', 'secret' => '4subJgKfmnRHaZGWRr6cbAJ6']],
        'Facebook' => [
            'enabled' => true,
            // 'scope'   => ['email', 'user_about_me', 'user_birthday', 'user_hometown']
            'keys' => [ 'id'  => '132906143984825', 'secret' => '0a6b2eff6b61e0942f2c4e3371d0881d'],
            'display' => 'popup',
        ]
    ]
];

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

// error if no provider specified
if ( !isset( $_GET['provider'] ) ) {
    set_and_return_error( 'Provider not set' );
} else {
    $_GET['provider'] = urldecode( $_GET['provider'] );
}

// Global unhelpful try/catch to obscure error messages
try {
    try{
        // Perform the API authentication request
        $hybridauth = new Hybridauth($config);
        $adapter = $hybridauth->getAdapter( stripslashes_deep( $_GET['provider'] ) );

        // Err if cookie not found
        if ( !isset( $_COOKIE[ strtolower( $_GET['provider'] ) . 'Token' ] ) )
            set_and_return_error('Authentication cookie not found.' . 
                '  Cookie sought: ' . strtolower( $_GET['provider'] ) . 'Token.' . 
                '  Cookies: ' . var_export($_COOKIE, true));

        $adapter->setAccessToken( json_decode( stripslashes_deep( $_COOKIE[ strtolower( $_GET['provider'] ) . 'Token' ] ) ) );
        if ( $adapter->isConnected() ) {
            // Fetch user's contacts
            $user_contacts = $adapter->getUserContacts();
            // Return success and disconnect
            returnSuccess( $user_contacts );
            $adapter->disconnect();
        }
        else {
            set_and_return_error( "not connected" );
        }
    }
    catch( Exception $e ){
        set_and_return_error( 'Oops, we ran into an issue! ' . $e->getMessage() );
    }
} catch ( Exception $e ) {
    // set_and_return_error( "Unspecified error" );
    set_and_return_error( $e->getMessage() );
}

// Return successfully
function returnSuccess( $return_value ) {
    $return_array = array(
        "success",
        $return_value,
    );

    echo json_encode( $return_array );
    die();
}

// Return an error via the expected JSON format
function set_and_return_error( $err_string ) {
    $return_array = array(
        "error" => true,
        "error_msg" => "Server: " . $err_string,
    );

    echo json_encode( $return_array );
    die();
}