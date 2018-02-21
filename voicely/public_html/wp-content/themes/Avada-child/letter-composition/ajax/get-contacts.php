<?php
session_start();

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow any host site to access this script
// header('Access-Control-Allow-Origin: *');
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

// error if no token specified
if ( !isset( $_GET['token'] ) )
    set_and_return_error( 'Token not set' );

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

        // Set token
        $adapter->setAccessToken( $_GET['token'] );
        if ( $adapter->isConnected() ) {
            // Fetch user's contacts
            $user_contacts = $adapter->getUserContacts();

            // Return success and disconnect
            returnSuccess( $user_contacts );
            $adapter->disconnect();
            die();
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
}

// Return an error via the expected JSON format
function set_and_return_error( $err_string ) {
    $return_array = array(
        "error" => true,
        "error_msg" => "Server: " . $err_string,
    );

    echo json_encode( $return_array );
}