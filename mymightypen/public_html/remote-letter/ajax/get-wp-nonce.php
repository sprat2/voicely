<?php

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../wp-load.php');

// Set the provider
if ( isset( $_GET['nonce_action'] ) ) {

    // Global unhelpful try/catch to obscure error messages
    try {
        switch ( $_GET['nonce_action'] ) {
            case 'post':
                echo json_encode( array(
                    'action' => $_GET['nonce_action'],
                    'nonce' => wp_create_nonce( 'post letter' ) 
                ) );
                return;
            case 'mark-shared':
                echo json_encode( array(
                    'action' => $_GET['nonce_action'],
                    'nonce' => wp_create_nonce( 'mark letter as shared' )
                ) );
                return;
            case 'share-to-social-media':
                echo json_encode( array(
                    'action' => $_GET['nonce_action'],
                    'nonce' => wp_create_nonce( 'share letter to social media' )
                ) );
                return;
            default:
                set_and_return_error( 'Nonce action was not an expected action.' );
        }

        set_and_return_error( 'Bypassed nonce handler.\n' );

    } catch ( Exception $e ) {
        set_and_return_error( 'Error while generating nonce.\n' . $e->getMessage() );
    }
} else {
    set_and_return_error( 'Nonce action not set' );
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
