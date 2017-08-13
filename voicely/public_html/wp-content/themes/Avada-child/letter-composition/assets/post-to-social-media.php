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
require 'hybridauth-credentials.php';

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
        $adapter = $hybridauth->getAdapter( stripslashes_deep( $_GET['provider'] ) );
        //set_and_return_error( stripslashes_deep( $_COOKIE[ strtolower( $_GET['provider'] ) . 'Token' ] ) );
        if ( !isset( $_COOKIE[ strtolower( $_GET['provider'] ) . 'Token' ] ) )
            set_and_return_error( 'Not authorized.' .
                '  Authentication cookie not found.' . 
                '  Likely the result of user not granting access to their third party account.' . 
                '  Cookie sought: ' . strtolower( $_GET['provider'] ) . 'Token.' . 
                '  Cookies: ' . var_export($_COOKIE, true));
        $adapter->setAccessToken( json_decode( stripslashes_deep( $_COOKIE[ strtolower( $_GET['provider'] ) . 'Token' ] ) ) );
        if ( $adapter->isConnected() ) {
            // Share to twitter. Twitter currently errors with the preferred way of link sharing in HybridAuth.
            // NOTE: Twitter links are automatically shortened to 23 chars, regardless of original length
            if ( strtolower( $_GET['provider'] ) == 'twitter' )
                $adapter->setUserStatus( urldecode( stripslashes_deep( $_GET['message'] ) . ' ' . urldecode( stripslashes_deep( $_GET['url'] ) ) ) );
            // Share to Facebook/others.  This is the preferred way to share links with HybridAuth.
            else {
                $adapter->setUserStatus( 
                    array( 
                        'message' => urldecode( stripslashes_deep( $_GET['message'] ) ),
                        'link'    => urldecode( stripslashes_deep( $_GET['url'] ) ),
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