<?php
wp_enqueue_script("jquery-ui-core");
wp_enqueue_script("jquery-ui-draggable");
wp_enqueue_script("jquery-ui-droppable");
?>
<!-- Displayed HTML container -->
<div class="container-fluid Col2" id="html-display-container">
  <!-- Header -->
  <div class="row">
    <div class="col-md-12" id="logo-header-div-parent">
      <div class="page-header" id="logo-header-div">
        <img src="https://voicely.org/wp-content/themes/Avada-child/letter-composition/img/Open_Letter_Logo.jpg" ALT="Logo" width="144" height="42" />
        <span id="blocking-login-overlay-element-wrapper">
          <h4>Your Open Letter</h4>
          <div id="body-blocking-overlay" data-logged-in=<?= is_user_logged_in() ? 'true' : 'false'; ?>>
            <span>
              <p>
                <a class="btn btn-block btn-social btn-facebook btn-facebook-overlay btn-inline" id="fb-signin-button">
                <span class="fa fa-facebook"></span> Log in with Facebook
                </a> &nbsp and write something important!
              <p>
            </span>
          </div>
        </span>
      </div>
    </div>
  </div>

  <div id="tokenholder" style="display: none;"></div>
  <div id="nonceholder" style="display: none;"></div>
  
  <!-- Left and right sides -->
  <div class="row" id="main-row">

    <!-- Left side -->
    <div class="col-md-7 panel" id="text-inputs">
      <!-- To: -->
      <span id="to-input-wrapper">
        <input type="text" placeholder="To" id="toInput" data-role="tagsinput" autocomplete="off" disabled>
      </span>
      <br>
      <!-- Title: -->
      <input type="text" placeholder="Title" id="titleInput" autocomplete="off" disabled>
      <br>
      <!-- Body: -->
      <span id="body-input-wrapper">
        <textarea class="form-control" placeholder="Write something important" id="bodyInput" autocomplete="off" disabled></textarea>
      </span>
    </div>
      
    <!-- Right side -->
    <div class="col-md-5 right-side-bar panel">
      <div id="right-side-bar-child">
        <!-- Sidebar -->
        <div id="div-display-window">
          <div id="all-inner-divs">
            <!-- Only fill this element with div elements of class "inner" -->
            <!--
            Old sidebar panels disabled for now
            <div class="inner">
              <?php // include 'sidebar-composition.php'?>
            </div>
            <div class="inner">
              <?php // include 'sidebar-facebook.php'?>
            </div>
            <div class="inner">
              <?php // include 'sidebar-twitter.php'?>
            </div>
            <div class="inner">
              <?php // include 'sidebar-google.php'?>
            </div>
            <div class="inner">
              <?php // include 'sidebar-windowslive.php'?>
            </div>
            <div class="inner">
              <?php // include 'sidebar-fax-hardcopy.php'?>
            </div>
            <div class="inner">
              <?php // include 'sidebar-payment.php'?>
            </div>
            -->
            <div class="inner">
              <?php include 'sidebar-composition.php'?>
              <div id="sharing-divs">
                <?php include 'sidebar-facebook.php'?>
                <?php include 'sidebar-twitter.php'?>
                <?php include 'sidebar-google.php'?>
                <?php include 'sidebar-windowslive.php'?>
                <?php // include 'sidebar-email.php'?>
                <?php include 'sidebar-fax-hardcopy.php'?>
              </div>
              <?php // include 'sidebar-payment.php'?>
              <?php include 'sidebar-postscreen.php'?>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Bottom Arrow Navigation -->
      <!--
      Old sidebar panels disabled for now
      <div id="last-section">
        <button id="sidebar-left" type="button" class="btn btn-default glyphicon glyphicon-chevron-left"></button>
        <button id="sidebar-right" type="button" class="btn btn-default glyphicon glyphicon-chevron-right"></button>
      </div>
      -->
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/composition.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/composition-scrollelements.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/composition-validation.js"></script>