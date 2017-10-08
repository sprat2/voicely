<div class="row" id="fax-sharing-div">
  <div class="col-xs-9 social-btn-div">
    <span>
      <a class="btn btn-block btn-social btn-github" id="fax-prompt-button">
        <span class="fa fa-fax"></span> Send Fax
      </a>
    </span>
  </div>
  <div class="col-xs-3">
    <a id="fax-skip-button" class="skip-button">No thanks...</a>
  </div>
</div>

<textarea id="fax-sharing-message" style="display: none;"></textarea>

<div class="row" id="hardcopy-sharing-div">
  <div class="col-xs-9 social-btn-div">
    <span>
      <a class="btn btn-block btn-social btn-adn" id="hardcopy-prompt-button">
        <span class="fa fa-envelope"></span> Send Hardcopy
      </a>
    </span>
  </div>
  <div class="col-xs-3">
    <a id="hardcopy-skip-button" class="skip-button">No thanks...</a>
  </div>
</div>

<textarea id="hardcopy-sharing-message" style="display: none;"></textarea>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/fax-hardcopy.js"></script>