<!-- Sharing to social networks -->
<div class="flex-display" id="social-networks-sharing-div">
  <span class="status-display-button"><i class="fa-alph">1</i></span>
  <span class="btn-social-span">
    <a class="btn btn-block btn-social btn-grey" id="social-networks-prompt-button">
      <span class="fa fa-share-alt"></span>
      <span class="social-prompt-text">Share to Social Networks</span>
    </a>
  </span>
  <span id="social-networks-status-indicator" class="status-display-button updatable-status"></span>
</div>

<!-- Sharing to social networks (Overlay) -->
<div id="social-networks-selection-overlay" class="overlay-background">
  <div id="social-networks-selection-overlay-content" class="overlay-content">
    <div id="social-networks-selection-div">
      <?php include 'sidebar-buttongroups-socialshare.php' ?>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/buttonsection-social-networks.js"></script>




<!-- Sharing to email contacts -->
<div class="flex-display" id="email-contacts-sharing-div">
  <span class="status-display-button"><i class="fa-alph">2</i></span>
  <span class="btn-social-span">
    <a class="btn btn-block btn-social btn-grey" id="email-contacts-prompt-button">
      <span class="fa fa-send"></span>
      <span class="social-prompt-text">Send via Email</span>
    </a>
  </span>
  <span id="email-contacts-status-indicator" class="status-display-button updatable-status"></span>
</div>

<!-- Sharing to email contacts (Overlay) -->
<div id="email-contacts-selection-overlay" class="overlay-background">
  <div id="email-contacts-selection-overlay-content" class="overlay-content">
    <div id="email-contacts-selection-div">
      <?php include 'sidebar-google.php' ?>
      <!-- <br style="font-size: 0%; line-height: 0.1;"> -->
      <?php include 'sidebar-windowslive.php' ?>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/buttonsection-email.js"></script>




<!-- Sharing via Fax/Hardcopy -->
<div class="flex-display" id="fax-hardcopy-sharing-div">
  <span class="status-display-button"><i class="fa-alph">3</i></span>
  <span class="btn-social-span">
    <a class="btn btn-block btn-social btn-grey" id="fax-hardcopy-prompt-button">
      <span class="fa fa-inbox"></span>
      <span class="social-prompt-text">Send Fax and Hardcopy</span>
    </a>
  </span>
  <span id="fax-hardcopy-networks-status-indicator" class="status-display-button updatable-status"></span>
</div>

<!-- Sharing via Fax/Hardcopy (Overlay) -->
<div id="fax-hardcopy-selection-overlay" class="overlay-background">
  <div id="fax-hardcopy-selection-overlay-content" class="overlay-content">
    <div id="fax-hardcopy-selection-div">
      <?php include 'sidebar-fax-hardcopy.php' ?>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/buttonsection-fax-hardcopy.js"></script>



<?php // include 'sidebar-facebook.php' ?>
<?php // include 'sidebar-twitter.php' ?>
<?php // include 'sidebar-google.php' ?>
<?php // include 'sidebar-windowslive.php' ?>
<?php // include 'sidebar-fax-hardcopy.php' ?>