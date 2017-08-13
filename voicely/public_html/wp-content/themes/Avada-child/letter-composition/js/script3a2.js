/* This file contains the HTML display code for step 3 - social auth - Twitter */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var detS = "";
  if (location.protocol == 'https:') detS = "s";
  var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/";
  
  // Load sharing JS, then execute share function
  $.getScript(ajaxLocation+"js/social-sharing.js", function(){

    // Set the sharing message appropriately
    $('#sharing-message').val( getShareMessageWithCurrentParams() );

    // Set up the page's buttons to perform their actions

    // Sharing prompt button
    $('#prompt-button').click(function() {
      // Store Facebook token as a cookie, to be used later by postToSocialMedia
      getToken('Twitter');

      // Enable the "Next" button and unhide the textarea
      $('#end-step3a2-button').prop('disabled', false);
      $('#sharing-message').css( 'display', 'inline' );
    });

    // Skip button
    $('#skip-button').click(function() {
      // Enable the "Next" button
      $('#end-step3a2-button').prop('disabled', false);
    });

  });

  // Set "next" button up to share data from this step and set up the next
  $('#end-step3a2-button').click(function() {
    // Save TW sharing data so we may share at the end
    $('#persistent-data-container').data('tw-sharing-message', $('#sharing-message').val());

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step3b1.php', function() {
        $.getScript(ajaxLocation+'js/script3b1.js');
    });
  });

})( jQuery );
