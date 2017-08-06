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
    Email, Facebook, Twitter, Instagram
    <br>
    Authentication, Sharing Message Edit, Friend Selection(+Harvesting), Sharing(+Success/Failure)
    <br>
</small>

<br>
Facebook
<button id="prompt-button">Prompt</button>
<button id="share-button">Share</button>

<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step3a-button">NEXT</button>
