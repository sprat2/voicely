<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../wp-load.php');

// Set the provider
if ( isset( $_GET['post_id'] ) && isset( $_GET['provider'] ) ) {
    // Global unhelpful try/catch to obscure error messages
    try {
        if ( strtolower( $_GET['provider'] ) == 'facebook' ) {
            $key_name = 'shared_to_FB';
        }
        elseif ( strtolower( $_GET['provider'] ) == 'twitter' ) {
            $key_name = 'shared_to_TW';
        }
        else {
            set_and_return_error( 'Provider not as expected.' );
        }

        // auto-sanitized (prepared) via WP's add_post_meta function
        add_post_meta( $_GET['post_id'], $key_name, 'its_a_flag', true );
        returnSuccess();
    } catch ( Exception $e ) {
        set_and_return_error( 'Error while marking post as shared.\n' . $e->getMessage() );
    }
} else {
    set_and_return_error( 'Post ID or social provider not set' );
}

// Return an error via the expected JSON format
function set_and_return_error( $err_string ) {
    $return_array = array(
        "error" => true,
        "error_msg" => "Server: " . $err_string,
    );

    echo json_encode( $return_array );
}

// Return success via the expected JSON format
function returnSuccess() {
    if ( isset( $_GET['provider'] ) ) {
        $prov = $_GET['provider'];
    }
    else
        $prov = "unknown provider";
    
    $return_array = array(
        "Success",
        "post marked as posted to " . $prov . '.' ,
    );

    echo json_encode( $return_array );
}
?>