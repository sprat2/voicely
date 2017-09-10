<?php
header('Content-Type: application/json');

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
  throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});
// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../../../../wp-load.php');

// Import Hybridauth
include '../vendor/autoload.php';
use Hybridauth\Hybridauth;
// Hybridauth configuration array
require 'hybridauth-credentials.php';


// Global unhelpful try/catch to obscure error messages
try {
  if ( !isset( $_GET['provider'] ) )
    set_and_return_error( 'Provider not set' );
  if ( !isset( $_GET['token'] ) )
    set_and_return_error( 'token not set' );
  if ( is_user_logged_in() )
    set_and_return_error( 'user already logged in' );
    
  // Get user profile data
  // Perform the API authentication request
  $hybridauth = new Hybridauth($config);
  $adapter = $hybridauth->getAdapter( stripslashes_deep( $_GET['provider'] ) );
  // Set token
  $adapter->setAccessToken( $_GET['token'] );
  if ( $adapter->isConnected() ) {
    // Fetch user data
    $userdata = $adapter->getUserProfile();
    $adapter->disconnect();
    $user_email = $userdata->email;
    $username = $userdata->displayName;
  }
  else {
      set_and_return_error( "adapter not connected" );
  }

  $user_id = email_exists( $user_email );
  if ( $user_id != false ) {
    // Sign the user in and refresh
    wp_set_auth_cookie( $user_id );
    echo json_encode( 'User has been logged in' );
    die();
  }
  else {
    // Create account for this user, sign them in, and refresh
    wp_create_user( $username, wp_generate_password(), $user_email );
    wp_set_auth_cookie( $user_id );
    echo json_encode( 'New account registered and user has been logged in' );
    die();
  }

  set_and_return_error('Bypassed expected control flow');  
} catch ( Exception $e ) {
  set_and_return_error( $e->getMessage() );
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