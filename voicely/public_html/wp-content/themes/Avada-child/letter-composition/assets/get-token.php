<?php
session_start();

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// NOTE: Does not interface with WP/DB at all.  Does not need sanitization for our purposes.

// Import Hybridauth
include '../vendor/autoload.php';
use Hybridauth\Hybridauth;

// Hybridauth configuration array
require 'hybridauth-credentials.php';

?>
<script>
// Script to return resulting data to calling window
function close_myself( result ) {
    window.opener.closePopupFromPopup( result );
    window.close();
    return false;
}
</script>
<?php

// Set the provider
if ( isset( $_GET['provider'] ) ) {
    $_SESSION['pastProvider'] = $_GET['provider'];
    $provider = $_GET['provider'];
} elseif ( isset( $_SESSION['pastProvider'] ) ) {
    $provider = $_SESSION['pastProvider'];
    unset( $_SESSION['pastProvider'] );
} else {
    set_and_return_error( 'Provider not set' );
}

// Global unhelpful try/catch to obscure error messages
try {
    try{
        // Perform the API authentication request
        $hybridauth = new Hybridauth($config);
        $adapter = $hybridauth->authenticate($provider); 
        if ( $adapter->isConnected() ) {
            if ( is_null( $adapter->getAccessToken() ) )
                header("Refresh:0");
            write_result( $adapter->getAccessToken() );
            if ( strtolower($provider) != 'twitter') // Bug fix - Don't disconnect Twitter's adapter
                $adapter->disconnect();
            die();
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
    unset( $_SESSION['pastProvider'] );
    write_result( $return_array );
    die();
}

// Writes a json param to a cookie, prefixed by either the provider name or "error"
function write_result( $response ) {
    // echo var_export($response, true);
    // echo '<br><br><br>';
    // echo var_export( $_COOKIE, true );
    echo '<script>close_myself('.json_encode($response).');</script>';
}
?>