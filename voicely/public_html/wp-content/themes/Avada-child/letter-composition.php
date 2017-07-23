<?php
/**
 * Template Name: Letter Composition
 *
 * This is the template used for the letter composition flow.
 *   It consists of a persistent data container which holds flow data, as well as another
 *   container which contains the current step's HTML display.
 * This file references the letter-composition directory
 *
 * Each step has its own PHP and JS files in assets/step# and js/script# respectively,
 *   which handles its own duties, as well as queues up the next when conditionally appropriate.
 *
 */

// Do not allow directly accessing this file. (mandate WP)
if ( ! defined( 'ABSPATH' ) ) { exit( 'Direct script access denied.' ); }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Your Open Letter</title>
    <link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/typeaheadjs.css" rel="stylesheet">
    <link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/style.css" rel="stylesheet">
  </head>
  <body>
	<?php get_header(); // Avada's header ?>

	<!-- Invisible data container -->
	<div id="persistent-data-container" style="display:none"></div>

	<!-- Displayed HTML container -->
    <div class="container-fluid Col2" id="html-display-container"><p>Loading...</p></div>

    <?php do_action( 'avada_after_content' ); ?>
    <?php get_footer(); // Avada's footer ?>

    <!--<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/jquery.min.js"></script>-->
    <script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/bootstrap.min.js"></script>
    <script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/bootstrap-tagsinput.js"></script>
    <script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/typeahead.bundle.js"></script>
    <script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/initscript.js"></script>
  </body>
</html>
