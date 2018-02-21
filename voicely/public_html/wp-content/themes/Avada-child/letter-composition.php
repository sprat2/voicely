<?php
// NOTE: HSTS may cause social sharing errors - not sure (workaround SSL enforcement below)
// header( 'Strict-Transport-Security: max-age=10886400' );
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

// Force SSL/HTTPS
if (empty($_SERVER['HTTPS'])) {
  wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
  die();
}
?>
<!-- Header -->
<?php get_header(); // Avada's header ?>

<!-- CSS -->
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/bootstrap.min.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/bootstrap-tagsinput.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/typeaheadjs.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/image-picker.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/multi-select.dist.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/bootstrap-social.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/thirdparty/font-awesome.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>letter-composition/css/style.css" rel="stylesheet">

<!-- Scripts -->
<!-- Must be imported here to be used by the content -->
<?php // (JQuery is included in WordPress) ?>
<!--<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/jquery.min.js"></script>-->
<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/thirdparty/bootstrap.min.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/thirdparty/bootstrap-tagsinput.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/thirdparty/image-picker.min.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/thirdparty/jquery.multi-select.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/thirdparty/typeahead.bundle.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>letter-composition/js/shared.js"></script>

<!-- Our content -->
<?php
  // If user isn't logged in, display the login screen. Otherwise display the page as expected.
  // Note: Disabled old login scheme
  // if ( ! is_user_logged_in() )
    // include "letter-composition/partials/login.php";
  // else
    include "letter-composition/partials/letter-composition.php";
?>

<!-- Footer -->
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer(); // Avada's footer ?>