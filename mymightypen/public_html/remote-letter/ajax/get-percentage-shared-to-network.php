<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../wp-load.php');

// Set the provider
if ( isset( $_GET['provider'] ) ) {

    // Global unhelpful try/catch to obscure error messages
    try {
        // Set the flag string depending on the social network requested
        if ( strtolower( $_GET['provider'] ) == 'facebook' ) {
            $prov_str = 'shared_to_FB';
        }
        elseif ( strtolower( $_GET['provider'] ) == 'twitter' ) {
            $prov_str = 'shared_to_TW';
        }
        else
            set_and_return_error( 'Unknown Provider' );

        // Query for the number of letters
        $query = new WP_Query( array( 'post_type' => 'letter' ) );
        $totalLetters = $query->found_posts;
        if ( $totalLetters == 0 )
            echo json_encode( "100" );

        // Query for the number of posts with the flag
        $query = new WP_Query( array( 'post_type' => 'letter', 'meta_key' => $prov_str ) );
        $numShared = $query->found_posts;

        // Calculate
        $percentageShared = $numShared / ((float) $totalLetters);
        $wholePercentageShared = (int) ( $percentageShared * 10000 / 100 );

        // Return the percentage of posts shared
        $returnArray = array(
            "result" => $wholePercentageShared,
            "provider" => $_GET['provider'],
        );
        echo json_encode( $returnArray );

    } catch ( Exception $e ) {
        set_and_return_error( 'Error while fetching number of shares.\n' . $e->getMessage() );
    }
} else {
    set_and_return_error( 'Provider not set' );
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
