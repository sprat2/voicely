<?php

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

// Return
echo json_encode( is_user_logged_in() );