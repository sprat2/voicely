<?php
/**
 * Template Name: Facebook Login Page
 */
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 
// Do not allow directly accessing this file. (mandate WP)
if ( ! defined( 'ABSPATH' ) ) { exit( 'Direct script access denied.' ); }

// Force SSL/HTTPS
if (empty($_SERVER['HTTPS'])) {
  wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
  die();
}
// If user is logged in, redirect to site's main page
if ( is_user_logged_in() ) {
  wp_redirect( get_bloginfo('url') );
  die();
}
?>
<!-- Header -->
<?php get_header(); // Avada's header ?>

<!-- CSS -->
<link href="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/css/thirdparty/bootstrap.min.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/css/thirdparty/bootstrap-tagsinput.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/css/thirdparty/bootstrap-social.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/css/thirdparty/font-awesome.css" rel="stylesheet">
<link href="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/css/style.css" rel="stylesheet">

<!-- User isn't logged in - Display the login screen -->
<div id="facebook-signin-button-div">
  <a class="btn btn-block btn-social btn-facebook" id="fb-signin-button">
    <span class="fa fa-facebook"></span> Sign in with Facebook
  </a>
</div>

<!-- Scripts -->
<!-- Must be imported here to be used by the content -->
<?php // (JQuery is included in WordPress) ?>
<!--<script src="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/js/jquery.min.js"></script>-->
<script src="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/js/login.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/js/thirdparty/bootstrap.min.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/js/thirdparty/bootstrap-tagsinput.js"></script>
<script src="<?= get_stylesheet_directory_uri().'/'?>fb-login-page/js/shared.js"></script>

<!-- Footer -->
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer(); // Avada's footer ?>