<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../wp-load.php');

// Set the provider
if ( isset( $_GET['given_person'] ) ) {
    // Global unhelpful try/catch to obscure error messages
    try {

        // // Get terms
        // $addressees = get_terms( 
        //     array( 
        //         'taxonomy'   => 'addressee', 
        //         'hide_empty' => false,
        //         'number'     => '30',
        //         'fields'     => 'names',
        //         'name__like' => $_GET['given_person'],
        //     ) 
        // );

        // // Get term metadata
        // $addressees_meta = [];
        // for ( $i = 0; $i < count( $addressees ); $i++ ) {
        //     $addressees_meta[] = get_term_by( 'name', $addressees[$i], 'addressee' );
        // }

        global $wpdb;
        $query = 
            "
            /* Get addressee term IDs */
            /*SELECT term_id 
            FROM  $wpdb->term_taxonomy
            WHERE taxonomy = 'addressee'
            */
            /* Join addressee term IDs to */
            SELECT *
            FROM $wpdb->termmeta
            ";
            
        $results = $wpdb->get_results( $query );

        echo json_encode( $results );
        // echo json_encode( $addressees_meta );

    } catch ( Exception $e ) {
        set_and_return_error( 'Error while fetching people.\n' . $e->getMessage() );
    }
} else {
    set_and_return_error( 'Person not set' );
}

// Return an error via the expected JSON format
function set_and_return_error( $err_string ) {
    $return_array = array(
        "error" => true,
        "error_msg" => "Server: " . $err_string,
    );

    echo json_encode( $return_array );
}
