<?php

/* This file contains the HTML display code for step 1 - letter composition */

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
<!-- Header -->
<div class="row">
<div class="col-md-12" id="logo-header-div-parent">
    <div class="page-header" id="logo-header-div">
    <img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/Open_Letter_Logo.jpg" ALT="Logo" width="144" height="42">
    <h4>Your Open Letter</h4>
    </div>
</div>
</div>

<!-- Left and right sides -->
<div class="row">

<!-- Left side -->
<div class="col-md-9" id="text-inputs">
    <form role="form">

    <!-- To: -->
    <div class="form-group">
        <input type="text" class="form-control" placeholder="To" id="toInput" data-role="tagsinput">
    </div>

    <!-- Title: -->
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Title" id="titleInput">
    </div>

    <!-- Body: -->
    <div class="form-group">
        <textarea type="message" style="height:325px;" class="form-control" placeholder="Write something important" id="bodyInput"></textarea>
    </div>

    </form>
</div>

<!-- Right side -->
<div class="right-side-bar">
    <div class="col-md-3" id="right-side-bar-child">
    <form role="form">
        <div class="form-group">

        <!-- Tags input -->
        <h4>Tags</h4>
        <textarea type="text" data-role="tagsinput" style="height:136px" placeholder="Add a tag..." class="form-control" id="tagsInput"></textarea>

        <!-- Related Tags button -->
        <button id="related-tags-button" type="button" class="btn btn-default" data-toggle="popover" data-placement="bottom" data-container="body" data-html="true" data-content="Fetching...">Related Tags</button>
        
        <!-- Related recipients display -->
        <h3>More recipients</h3>
        <div class="more-recipients" id="more-recipients">
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
            <a href="#"><img src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/block_small.jpg" alt="recipients" width="44" height="44"></a>
        </div>

        </div>
    </form>
    </div>
</div>

</div>

<!-- "Next" button -->
<div class=form-progress id="composition-progress-button-div">
<button type="Next" class="btn btn-large btn-primary" id="end-composition-button" style="width: 135px;">NEXT</button>
</div>