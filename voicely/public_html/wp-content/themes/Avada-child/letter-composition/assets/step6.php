<?php

/* This file contains the HTML display code for step 6 - "thank you"/finished screen */

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

<!-- "Submitted" dialog. Hidden initially. Filled out dynamically. -->
<div id="submit-view-container">
  <div id="submit-view">
    <div id="alert-bar">
      <div id="no-share-warning" class="alert alert-warning" role="alert" style="display: none;">
        <strong>Speak up!</strong> Letters that are shared get more attention!
      </div>
    </div>
    <div id="letter-progress">
      <span class="sharing-icon"></span>
      <span class="message"></span>
    </div>
    <div id="fb-share-progress">
      <span class="sharing-icon"></span>
      <span class="message"></span>
    </div>
    <div id="tw-share-progress">
      <span class="sharing-icon"></span>
      <span class="message"></span>
    </div>
    <div id="mailto-link">
    </div>
    <div id="url-to-clipboard">
    </div>
  </div>
</div>

<br>
Thank you

<br>
<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step6-button">FINISH</button>
