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
header('Access-Control-Allow-Origin: *');

// NOTE: Does not interface with WP/DB at all.  Does not need sanitization for our purposes.

// Import Hybridauth
include '../vendor/autoload.php';
use Hybridauth\Hybridauth;

// Hybridauth configuration array
require 'hybridauth-credentials.php';

// Set the provider
if ( isset( $_GET['provider'] ) ) {
    $_SESSION['pastProvider'] = urldecode( $_GET['provider'] );
    $provider =  urldecode( $_GET['provider'] );
} elseif ( isset( $_SESSION['pastProvider'] ) ||
           isset( $_GET['oauth_token'] ) ||
           isset( $_GET['code'] ) ) {
    $provider = $_SESSION['pastProvider']; // Already set
    unset( $_SESSION['pastProvider'] );
} else {
    set_and_return_error( 'Provider not set' );
    unset( $_SESSION['pastProvider'] );
}

// Global unhelpful try/catch to obscure error messages
try {
    try{
        // Perform the API authentication request
        $hybridauth = new Hybridauth($config);
        $adapter = $hybridauth->authenticate($provider); 
        if ( $adapter->isConnected() ) {
            write_to_cookie( json_encode( $adapter->getAccessToken() ), $provider );
            $adapter->disconnect();
        }
        else {
            set_and_return_error( "not able to connect" );
        }
    }
    catch( Exception $e ){
        set_and_return_error( 'Oops, we ran into an issue! ' . $e->getMessage() );
    }
} catch ( Exception $e ) {
    // set_and_return_error( "Unspecified error" );
    set_and_return_error( $e->getMessage() );
}

// Return an error via the expected JSON format
function set_and_return_error( $err_string ) {
    $return_array = array(
        "error" => true,
        "error_msg" => "Server: " . $err_string,
    );

    unset( $provider );
    write_to_cookie( json_encode( $return_array ) );
}

// Writes a json param to a cookie, prefixed by either the provider name or "error"
function write_to_cookie( $response, $provider = 'error' ) {
    $provider = strtolower( $provider );
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    setcookie( $provider . "Token", $response, 0 ); // Expires on session end

    echo '<script>window.close();</script>';
    // echo var_export($response, true);
    // echo '<br><br><br>';
    // echo var_export( $_COOKIE, true );
}
?>