<?php

/* This file contains the HTML display code for step 5 - Money collection */

$debug = true;

// Display errors if debugging
if ( $debug ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../../../../wp-load.php')

?>
Money collection (if appropriate)
<br>
<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step5-button">NEXT</button>
