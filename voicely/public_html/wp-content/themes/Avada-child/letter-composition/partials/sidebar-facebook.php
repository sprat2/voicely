<div class="row" id="facebook-sharing-div">
  <div class="col-xs-9 social-btn-div">
    <span>
      <a class="btn btn-block btn-social btn-facebook" id="facebook-prompt-button">
        <span class="fa fa-facebook"></span> Share to Facebook
      </a>
    </span>
  </div>
  <div class="col-xs-3">
    <a id="facebook-skip-button" class="skip-button">No thanks...</a>
  </div>
</div>

<div class="row" id="facebook-sharing-message-content" style="display: none;">
  <div class="col-sm-5" >
    <textarea class="sharing-message-textarea" id="facebook-sharing-message-suggestion" disabled></textarea>
  </div>
  <div class="col-sm-2" id="fb-copyover-button-div" >
    <a class="btn btn-lg btn-link" id="fb-copyover-button">
      <span class="fa fa-2x fa-arrow-circle-right"></span>
    </a>
  </div>
  <div class="col-sm-5" >
    <textarea class="sharing-message-textarea" id="facebook-sharing-message"></textarea>
  </div>
</div>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/facebook.js"></script>