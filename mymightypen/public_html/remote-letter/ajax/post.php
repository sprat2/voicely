<?php

// Allow any host site to access this script
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Declare our response type as a JSON response
// May not work with current config
//header('Content-type: application/json');

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

// Global unhelpful try/catch to obscure error messages
try {

    // Load WordPress functionality
    define('WP_USE_THEMES', false);
    require('../../wp-load.php');
    // Fix naive copy hardlinks
    //  (There's a better way to do this, but it requires a file to be edited each time the server is copied)
    //  (The better way fixes all the links, so that the WP frontend may be used)
    define('WP_HOME','http://mymightypen.org');
    define('WP_SITEURL','http://mymightypen.org');

    // If form has been submitted, create the post and display sharing options
    if (( isset( $_POST['title'] ) ) &&
        ( isset( $_POST['contents'] ) ) ) {

        // Validate the input
        if (empty( $_POST['title'] ) ||
            empty( $_POST['contents'] ) ) {
            set_and_return_error( "Received empty parameter/s - must have a proper title and letter body" );
        }

        // else input's valid - set and sanitize input variables
        $title = sanitize_text_field( $_POST['title'] );
        $contents = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['contents'] ) ) );
        $tags = sanitize_text_field( $_POST['tags'] );
        // Set default addressee if none is provided
        if ( empty( $_POST['addressees'] ) )
            $addressees = sanitize_text_field( 'The World' );
        else
            $addressees = sanitize_text_field( $_POST['addressees'] );

        // Collect addressees & tags
        $addressees = explode( ',', $addressees );
        $tags = explode( ', ', $tags );

        // Filter out addressees which don't exist (likely only the result of parameter manipulation)
        foreach ($addressees as $key => $value) {
            if ( !term_exists( $value, 'addressee' ) ) {
                unset( $addressees[$key] );
            }
        }

        // Create the post
        $post = wp_insert_post( array(
            'post_type' => 'letter',
            'post_content' => $contents,
            'post_title' => $title,
            'post_status' => 'publish',
            'tags_input' => $tags,
            // 'post_type' => 'letter', custom types not yet supported by Avada
            // 'post_category' => array( get_category_by_slug( 'letter' )->term_id ),
            // 'tax_input' => array(
            //     'addressee' => $addressees // doesn't work, apparently
            // ),
        ) );

        // Handle post insertion failure
        if ( is_wp_error( $post ) ) {
            $return_array = array(
                "error" => true,
                "error_msg" => "Failed to insert post<br>Reason:" . get_error_message($post),
            );
            echo json_encode( $return_array );
        }
        // Else insert the 'addressees' taxonomy links and return post's relevant data
        else {
            $term_result = wp_set_object_terms( $post, $addressees, 'addressee' );
            if ( is_wp_error( $term_result ) ) {
                set_and_return_error( "Unable to set the letter's addressees" );
            }

            try {
                $terms = wp_get_post_tags( $post );
                $tags = array();
                foreach ( $terms as $term ) {
                    $tags[] = $term->name;
                }
                $terms = wp_get_post_terms( $post, 'addressee' );
                $addressees = array();
                foreach ( $terms as $term ) {
                    $addressees[] = $term->name;
                }
            
                $return_array = array(
                    "url_to_letter" => get_post_permalink( $post ),
                    "comma_separated_tag_names" => implode( ",", $tags ),
                    "letter_title" => get_the_title( $post ),
                    "comma_separated_addressees" => implode( ",", $addressees ),
                    "post_id" => $post,
                );

                echo json_encode( $return_array );
            }
            // Handle errors in post's data fetching
            catch ( Exception $e ) {
                set_and_return_error( "Unable to retrieve letter's information\n" . $e->getMessage() );
            }
        }
    }
    else {
        set_and_return_error( "POST parameters not as expected - requires at least a title & letter body" );
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

    echo json_encode( $return_array );
    die();
}