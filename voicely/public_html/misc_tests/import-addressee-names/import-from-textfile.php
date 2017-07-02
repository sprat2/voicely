<?php
/*
 * DO NOT RUN THIS SCRIPT MORE THAN ONCE FOR EACH SET OF NEW ADDRESSEES!
 */

// Set behavior
$ERASE_ALL = false; // DO NOT BE NEGLIGENT WITH THIS VALUE!
$ADD_NAMES = false;

echo 'script started.<br><br>';

// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include WP core
require('../../wp-load.php');

if ( $ERASE_ALL === true ) {
    // Deletes all addressees
    $terms = get_terms( 'addressee', array( 'fields' => 'ids', 'hide_empty' => false ) );
    foreach ( $terms as $value ) {
        wp_delete_term( $value, 'addressee' );
    }

    echo 'all addressees deleted.<br><br>';
}

if ( $ADD_NAMES === true ) {
    //  Get & parse names from text file (newline-separated)
    echo 'parsing text file.<br>';
    ob_start();
    include('senate.txt');
    $names = ob_get_contents();
    ob_end_clean();
    // Trim whitespace from blob of names
    $names = trim($names);
    // Separate by newline
    $names = str_replace( "\n\r", "\r\n", $names );
    $names = str_replace( "\r\n", "\n", $names );
    $names = explode( "\n", $names );
    // Remove whitespace from each name
    array_map('trim', $names);
    echo 'done parsing text file.<br><br>';

    // Add addressees for each entry above
    foreach ( $names as $name ) {
        echo "started ($name).<br>";

        // twittify the addressee's name
        $twitter_name = str_replace(' ', '', $name);
        $twitter_name = '@'.$twitter_name;
        // Insert the user by their twittified name
        $result = wp_insert_term( $twitter_name, 'addressee' );
        $term_id = $result['term_id'];
        // Register the user's name as a metavalue
        add_term_meta( $term_id, 'full_name', $name, true );
        add_term_meta( $term_id, 'pretty_name', $name, true );

        echo "completed ($name).<br><br>";
    }
    unset($name);
}

echo 'script completed.';