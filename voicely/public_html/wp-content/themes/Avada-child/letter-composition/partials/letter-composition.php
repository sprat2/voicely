<!-- Displayed HTML container -->
<div class="container-fluid Col2" id="html-display-container">
  <!-- Header -->
  <div class="row">
    <div class="col-md-12" id="logo-header-div-parent">
      <div class="page-header" id="logo-header-div">
        <img src="https://voicely.org/wp-content/themes/Avada-child/letter-composition/img/Open_Letter_Logo.jpg" ALT="Logo" width="144" height="42" />
        <h4>Your Open Letter</h4>
      </div>
    </div>
  </div>

  <div id="tokenholder" style="display: none;"></div>
  <div id="nonceholder" style="display: none;"></div>
  
  <!-- Left and right sides -->
  <div class="row">

    <!-- Left side -->
    <div class="col-md-9" id="text-inputs">
      <!-- To: -->
      <input type="text" placeholder="To" id="toInput" data-role="tagsinput">
      <!-- Title: -->
      <input type="text" placeholder="Title" id="titleInput">
      <!-- Body: -->
      <textarea class="form-control" placeholder="Write something important" id="bodyInput"></textarea>
    </div>

    <!-- Right side -->
    <div class="right-side-bar">
      <div class="col-md-3" id="right-side-bar-child">
        <!-- Sidebar -->
        <div id="div-display-window">
          <div id="all-inner-divs">
            <!-- Only fill this element with div elements of class "inner" -->
            <div class="inner">
              <?php include 'sidebar-composition.php' ?>
            </div>
            <div class="inner" style="background-color: lightblue;">
              <?php include 'sidebar-facebook.php' ?>
            </div>
            <div class="inner" style="background-color: green;">
              <?php include 'sidebar-twitter.php' ?>
            </div>
            <div class="inner" style="background-color: gray;">
              <?php include 'sidebar-google.php' ?>
            </div>
            <div class="inner" style="background-color: red;">
              <?php include 'sidebar-windowslive.php' ?>
            </div>
            <div class="inner" style="background-color: yellow;">
              <?php include 'sidebar-fax-hardcopy.php' ?>
            </div>
            <div class="inner" style="background-color: cyan;">
              <?php include 'sidebar-payment.php' ?>
            </div>
            <div class="inner" style="background-color: blue;">
              <?php include 'sidebar-postscreen.php' ?>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Bottom Arrow Navigation -->
      <div id="last-section">
        <button id="sidebar-left" type="button" class="btn btn-default glyphicon glyphicon-chevron-left"></button>
        <button id="sidebar-right" type="button" class="btn btn-default glyphicon glyphicon-chevron-right"></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/composition.js"></script>