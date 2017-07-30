<?php

/* This file contains the HTML display code for step 3 - social auth */

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
Social auth:
<br>
Email, Facebook, Twitter, Instagram
<br>
Authentication, Sharing Message Edit, Friend Selection(+Harvesting), Sharing(+Success/Failure)
<br>
<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step3-button">NEXT</button>
