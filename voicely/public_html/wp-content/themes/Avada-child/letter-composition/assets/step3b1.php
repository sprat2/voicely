<?php

/* This file contains the HTML display code for step 3a - social auth - facebook */

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
    Email
    <br>
    Authentication, Sharing Message Edit, Friend Selection(+Harvesting), Sharing(+Success/Failure)
    <br>
    <br>
</small>

Gmail Contacts
<br>

<button id="prompt-button">Authorize with Gmail</button>
<button id="select-contacts-button" disabled>Select contacts</button>
<br>

<a id="skip-button">No thanks...</button>
<br>

<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step3b1-button" disabled>NEXT</button>
