<button id="post-now-button" type="button" class="btn btn-default">Submit Letter</button>

<!-- "Submitted" dialog. Hidden initially. Filled out dynamically. -->
<div id="submit-view-container">
  <div id="submit-view">
    <div id="alert-bar">
      <div id="no-share-warning" class="alert alert-warning" role="alert" style="display: none;">
        <strong>Speak up!</strong> Letters that are shared get more attention!
      </div>
    </div>
    <div id="letter-progress">
      <span class="sharing-icon"></span>
      <span class="message"></span>
    </div>
    <div id="fb-share-progress">
      <span class="sharing-icon"></span>
      <span class="message"></span>
    </div>
    <div id="tw-share-progress">
      <span class="sharing-icon"></span>
      <span class="message"></span>
    </div>
    <div id="mailto-link">
    </div>
    <div id="url-to-clipboard">
    </div>
  </div>
</div>

<br>
Thank you

<br>
<!-- "Next" button -->
<button class="btn btn-large btn-primary" id="end-step6-buttona" type="button" class="btn btn-default" disabled>View this letter</button>
<button class="btn btn-large btn-primary" id="end-step6-buttonc" type="button" class="btn btn-default" disabled>View my letters</button>
<button class="btn btn-large btn-primary" id="end-step6-buttonb" type="button" class="btn btn-default">View all letters</button>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/postscreen.js"></script>