<!-- Publish Button -->
<div id="tooltip">
  <button id="post-now-button" type="button" class="btn btn-primary center-block" disabled>Publish</button>
  <span id="tooltiptext" class="display-enabled"></span>
</div>

<!-- Overlay -->
<div id="postscreen-overlay">
  <div id="submit-view-container">

    <!-- Submit Summary -->
    <div id="submit-view">
      <div id="alert-bar">
        <div id="no-share-warning" class="alert alert-warning" role="alert" style="display: none;">
          <strong>Speak up!</strong> Letters that are shared get more attention!
        </div>
      </div>
      <div id="letter-progress" class="social-share-progress">
        <span class="sharing-icon"></span>
        <span class="message"></span>
      </div>
      <div id="fb-share-progress" class="social-share-progress">
        <span class="sharing-icon"></span>
        <span class="message"></span>
      </div>
      <div id="tw-share-progress" class="social-share-progress">
        <span class="sharing-icon"></span>
        <span class="message"></span>
      </div>
      <div id="mailto-link"></div>
      <div id="url-to-clipboard"></div>
    </div>

    <center>Thank You!</center>

    <!-- Buttons -->
    <div id="postscreen-buttons-div">
      <?php // This button is handled dynamically, as we don't have access to the letter's URL yet ?>
      <button class="btn btn-large btn-info" id="end-step6-buttona" type="button" disabled>View this letter</button>
      <form action="<?= get_author_posts_url( get_current_user_id() ) ?>" id="end-step6-buttonc-wrapper">
        <button class="btn btn-large btn-info" id="end-step6-buttonc" type="submit">View my letters</button>
      </form>
      <form action="/open-letters-2/" id="end-step6-buttonc-wrapper">
        <button class="btn btn-large btn-info" id="end-step6-buttonb" type="submit">View all letters</button>
      </form>
    </div>

  </div>
</div> 

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() ?>/letter-composition/js/postscreen.js"></script>