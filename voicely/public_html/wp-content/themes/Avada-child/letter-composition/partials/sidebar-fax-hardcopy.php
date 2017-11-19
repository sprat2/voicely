<div class="flex-display" id="fax-sharing-div">
  <span class="status-display-button"><i class="fa-alph">5</i></span>
    <span class="btn-social-span">
      <a class="btn btn-block btn-social btn-github disabled" id="fax-prompt-button">
        <span class="fa fa-fax"></span>
        <span class="social-prompt-text">Send Fax</span>
      </a>
    </span>
  <span class="status-display-button updatable-status"></span>
  <!-- <div class="col-xs-3"> -->
    <!-- <a id="fax-skip-button" class="skip-button">No thanks...</a> -->
  <!-- </div> -->
</div>

<textarea id="fax-sharing-message" style="display: none;"></textarea>

<div class="flex-display" id="hardcopy-sharing-div">
  <span class="status-display-button"><i class="fa-alph">6</i></span>
    <span class="btn-social-span">
      <a class="btn btn-block btn-social btn-adn disabled" id="hardcopy-prompt-button">
        <span class="fa fa-inbox"></span>
        <span class="social-prompt-text">Send Hardcopy</span>
      </a>
    </span>
  <span class="status-display-button updatable-status"></span>
  <!-- <div class="col-xs-3"> -->
    <!-- <a id="hardcopy-skip-button" class="skip-button">No thanks...</a> -->
  <!-- </div> -->
</div>

<textarea id="hardcopy-sharing-message" style="display: none;"></textarea>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/fax-hardcopy.js"></script>