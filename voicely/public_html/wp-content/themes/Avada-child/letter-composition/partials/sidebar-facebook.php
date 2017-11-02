<div class="flex-display" id="facebook-sharing-div">
  <span class="status-display-button"><i class="fa-alph">1</i></span>
  <span class="btn-social-span">
    <a class="btn btn-block btn-social btn-facebook disabled" id="facebook-prompt-button">
      <span class="fa fa-facebook"></span>
      <span class="social-prompt-text">Share to Facebook</span>
    </a>
  </span>
  <span id="fb-status-indicator" class="status-display-button updatable-status"></span>
  <!-- <div class="col-xs-3"> -->
    <!-- <a id="facebook-skip-button" class="skip-button">No thanks...</a> -->
  <!-- </div> -->
</div>

<div id="facebook-sharing-message-overlay-background" class="overlay-background">
  <div class="overlay-content">
    <div class="overlay-content-wrapper">
      <div id="facebook-sharing-message-content">
        <textarea class="sharing-message-textarea" id="facebook-sharing-message-suggestion" disabled></textarea>
        <span id="fb-copyover-button-div" >
          <a class="btn btn-lg btn-link" id="fb-copyover-button">
            <span class="fa fa-2x fa-arrow-circle-right"></span>
          </a>
        </span>
        <textarea class="sharing-message-textarea" id="facebook-sharing-message"></textarea>
      </div>
      <div id="close-fb-overlay-button-wrapper" class="pull-right">
        <button id="close-fb-overlay-button" type="button" class="btn btn-primary center-block pull-right">Share</button>
      </div>
    </div>
  </div>
</div>
  
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/facebook.js"></script>