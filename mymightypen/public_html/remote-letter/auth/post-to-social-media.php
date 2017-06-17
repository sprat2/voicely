<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Load WordPress functionality
//   (required for nonce verification - may lighten later by only importing required functionality)
define('WP_USE_THEMES', false);
require('../../wp-load.php');

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
        'Google'   => ['enabled' => false,'keys' => [ 'id'  => '822285507867-779tnut9hd0bpvkk54oikgk9276tsb0q.apps.googleusercontent.com', 'secret' => '4subJgKfmnRHaZGWRr6cbAJ6']],
        'Facebook' => [
            'enabled' => true,
            // 'scope'   => ['email', 'user_about_me', 'user_birthday', 'user_hometown']
            'keys' => [ 'id'  => '1365051533556262', 'secret' => '8306ed4c6f2e903e49769ed14e5f3d1d'],
            'display' => 'popup',
        ]
    ]
];

// Verify the nonce
if ( empty( $_GET['nonce'] ) ) {
    set_and_return_error( "No nonce provided" );
}
if ( !wp_verify_nonce( $_GET['nonce'], 'share letter to social media' ) ) {
    set_and_return_error( "Invalid nonce provided" );
}

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

// error if no sharing message specified
if ( !isset( $_GET['message'] ) ) {
    set_and_return_error( 'User message not set as expected' );
}

// error if no sharing message specified
if ( !isset( $_GET['url'] ) ) {
    set_and_return_error( 'URL to post not set as expected' );
}

// Global unhelpful try/catch to obscure error messages
try {
    try{
        // Perform the API authentication request
        $hybridauth = new Hybridauth($config);
        $adapter = $hybridauth->getAdapter(  $_GET['provider'] );
        $adapter->setAccessToken( json_decode( $_COOKIE[ strtolower( $_GET['provider'] ) . 'Token' ] ) );
        if ( $adapter->isConnected() ) {
            // Share to twitter. Twitter currently errors with the preferred way of link sharing in HybridAuth.
            // NOTE: Twitter links are automatically shortened to 23 chars, regardless of original length
            if ( strtolower( $_GET['provider'] ) == 'twitter' )
                $adapter->setUserStatus( urldecode( $_GET['message'] . ' ' . urldecode( $_GET['url'] ) ) );
            // Share to Facebook/others.  This is the preferred way to share links with HybridAuth.
            else {
                $adapter->setUserStatus( 
                    array( 
                        'message' => urldecode( $_GET['message'] ),
                        'link'    => urldecode( $_GET['url'] ),
                    )
                );
            }
            
            // Return success and disconnect
            returnSuccess();
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
function returnSuccess() {
    $return_array = array(
        "success",
        "posted to social media",
    );

    echo json_encode( $return_array );
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