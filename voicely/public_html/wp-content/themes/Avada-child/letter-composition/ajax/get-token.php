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
<!DOCTYPE html>
<script>
// Script to return resulting data to calling window
function close_myself( result, provider, syslogin ) {
  window.opener.closePopupFromPopup( result, provider, syslogin );
  window.close();
  return false;
}
</script>
<?php

// Set the provider
if ( isset( $_GET['provider'] ) ) {
  $_SESSION['pastProvider'] = $_GET['provider'];
  $_SESSION['pastSyslogin'] = isset( $_GET['syslogin'] ) ? 'true' : 'false';
  $provider = $_GET['provider'];
  $syslogin = isset( $_GET['syslogin'] ) ? 'true' : 'false';  
} elseif ( isset( $_SESSION['pastProvider'] ) ) {
  $provider = $_SESSION['pastProvider'];
  $syslogin = $_SESSION['pastSyslogin'];
  unset( $_SESSION['pastProvider'] );
  unset( $_SESSION['pastSyslogin'] );
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
      write_result( $adapter->getAccessToken(), $provider, $syslogin );
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

  write_result( $return_array );
  unset( $provider );
  unset( $_SESSION['pastProvider'] );
  unset( $_SESSION['pastSyslogin'] );
  die();
}

// Writes a json param to a cookie, prefixed by either the provider name or "error"
function write_result( $response, $provider = 'unknown provider', $syslogin = 'false' ) {
  echo '<script>' . PHP_EOL;
  echo 'close_myself('.json_encode($response).', "'.$provider.'", "'.$syslogin.'");' . PHP_EOL;
  echo '</script>' . PHP_EOL;
}
?>