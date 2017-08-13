<?php

/* This file contains the HTML display code for step 3a - social auth - twitter */

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

<small>
    Social auth:
    <br>
    Twitter
    <br>
    Authentication, Sharing Message Edit, Friend Selection(+Harvesting), Sharing(+Success/Failure)
    <br>
    <br>
</small>

Twitter<br>
<textarea id="sharing-message" style="display: none;"></textarea><br>

<button id="prompt-button">Share to Twitter</button>
<br>
<a id="skip-button">No thanks...</button>
<br>

<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step3a2-button" disabled>NEXT</button>
