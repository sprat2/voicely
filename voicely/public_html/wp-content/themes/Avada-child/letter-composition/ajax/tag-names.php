<?php
// This file returns all tags associated with letters
// XXX: This will likely need to be optimised in the future,
//      and the Bloodhound engine we're already using is perfect
//      for the task

$debug = true;

// Display errors if debugging
if ( $debug ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

header('Content-Type: application/json');

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../../../../wp-load.php');

// Get tags
$wp_tags_objects = get_terms( array( 
    'taxonomy' => 'post_tag',
    'hide_empty' => false, // whether or not to hide unused terms
) );

$wp_tags = array();
foreach ( $wp_tags_objects as $tag_object ) {
    $wp_tags[] = $tag_object->name;
}

// Return
echo json_encode( $wp_tags );