<div class="flex-display" id="twitter-sharing-div">
<span class="status-display-button"><i class="fa-alph">2</i></span>
<span class="btn-social-span">
  <a class="btn btn-block btn-social btn-twitter disabled" id="twitter-prompt-button">
    <span class="fa fa-twitter"></span>
    <span class="social-prompt-text">Share to Twitter</span>
  </a>
</span>
<span id="tw-status-indicator" class="status-display-button updatable-status"></span>
<!-- <div class="col-xs-3"> -->
  <!-- <a id="twitter-skip-button" class="skip-button">No thanks...</a> -->
<!-- </div> -->
</div>

<div id="twitter-sharing-message-overlay-background" class="overlay-background">
  <div class="overlay-content">
    <div class="overlay-content-wrapper">
      <div id="twitter-sharing-message-content">
        <textarea id="twitter-sharing-message"></textarea>
      </div>
      <div id="close-tw-overlay-button-wrapper" class="pull-right">
        <button id="close-tw-overlay-button" type="button" class="btn btn-primary center-block pull-right">Share</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/twitter.js"></script>