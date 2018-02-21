<div class="flex-display" id="email-sharing-div">
  <span class="status-display-button"><i class="fa-alph">X</i></span>
    <span class="btn-social-span">
      <a class="btn btn-block btn-social btn-vk disabled" id="email-prompt-button">
        <span class="fa fa-envelope"></span>
        <span class="social-prompt-text">Share to Email</span>
      </a>
    </span>
  <span class="status-display-button updatable-status"></span>
  <!-- <div class="col-xs-3"> -->
    <!-- <a id="email-skip-button" class="skip-button">No thanks...</a> -->
  <!-- </div> -->
</div>

<div id="email-contacts-selection-overlay" class="overlay-background">
  <div id="email-contacts-selection-overlay-content" class="overlay-content">
    <div id="email-contacts-selection-div"></div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/email.js"></script>