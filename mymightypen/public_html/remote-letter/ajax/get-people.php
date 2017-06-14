<?php
session_start();

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../wp-load.php');

// $wpdb->show_errors();

// Set the provider
if ( isset( $_GET['given_person'] ) ) {
    // Global unhelpful try/catch to obscure error messages
    try {

        global $wpdb;

        // Escape the user-input query param for its LIKE statements
        //   NOTE: Must still be SQL-escaped (by something like "prepare")
        $input = $wpdb->esc_like( $_GET['given_person'] );

        // Comments explain their following segments
        $query = $wpdb->prepare(
            "
            /* addressees which have meta-values or names LIKE the input value */
            (
                SELECT name, $wpdb->terms.term_id
                FROM $wpdb->terms
                INNER JOIN

                /* addressee IDs which have meta-values or names LIKE the input value */
                (

                    SELECT t_result1.term_id

                    FROM (
                        /* all addressee IDs */
                        SELECT term_id 
                        FROM $wpdb->term_taxonomy
                        WHERE taxonomy = 'addressee'
                    ) t_result1
                    
                    INNER JOIN (
                        
                        (
                            /* meta_values LIKE the input value */
                            SELECT DISTINCT term_id
                            FROM $wpdb->termmeta
                            WHERE meta_value 
                            LIKE %s
                        )

                        UNION

                        (
                            /* name LIKE the input value */
                            SELECT term_id 
                            FROM $wpdb->terms
                            WHERE name
                            LIKE %s
                        )

                    ) t_result2

                    ON t_result1.term_id = t_result2.term_id

                ) t_outer_result

                ON $wpdb->terms.term_id = t_outer_result.term_id
            )

            LIMIT 30
            ",
            '%' . $input . '%',
            '%' . $input . '%'
        );

        $returnArray = array(
            'result' => $wpdb->get_results( $query ),
            'input'  => $_GET['given_person']
        );
            

        echo json_encode( $returnArray );

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
