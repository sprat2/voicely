<?php
// This file returns all addressees associated with letters
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

// Get addressees
$wp_addressees_objects = get_terms( array( 
    'taxonomy' => 'addressee',
    'hide_empty' => false, // whether or not to hide unused terms
) );
$wp_addressees = array();
foreach ( $wp_addressees_objects as $addressee_object ) {
    $wp_addressees[] = $addressee_object->name;
}

// Return
echo json_encode( $wp_addressees );