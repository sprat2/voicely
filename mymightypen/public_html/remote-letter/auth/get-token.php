<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// NOTE: Does not interface with WP at all.  Does not need sanitization for our purposes.

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

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
//    (Diabled for now - viewing errors by accessing the page directly - not by viewing the cookie output)
// set_error_handler(function($errno, $errstr, $errfile, $errline) {
//     throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
// });

// Set the provider
if ( isset( $_GET['provider'] ) ) {
    $_SESSION['pastProvider'] = urldecode( $_GET['provider'] );
    $provider =  urldecode( $_GET['provider'] );
} elseif ( isset( $_SESSION['pastProvider'] ) ||
           isset( $_GET['oauth_token'] ) ||
           isset( $_GET['code'] ) ) {
    $provider = $_SESSION['pastProvider']; // Already sanitized on last visit
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
            write_to_cookie( json_encode( $adapter->getAccessToken() ), $provider );
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
    if ( isset( $provider ) ) {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        //setcookie( $provider . "Token", $response, 0, '/', $domain ); // Expires on session end
        setcookie( $provider . "Token", $response, 0 ); // Expires on session end
    }
    else {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        //setcookie( "errorToken", $response, 0, '/', $domain ); // Expires on session end
        setcookie( "errorToken", $response, 0 ); // Expires on session end
    }

    echo '<script>window.close();</script>';
    die();
}
?>