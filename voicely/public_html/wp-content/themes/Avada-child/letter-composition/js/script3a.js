/* This file contains the HTML display code for step 3 - social auth */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";

  // Load sharing JS, then execute share function
  $.getScript(ajaxLocation+"js/social-sharing.js", function(){

    // Set the sharing message appropriately
    $('#sharing-message').val( getShareMessageWithCurrentParams() );

    // Set up the page's buttons to perform their actions
    
    // Sharing prompt button
    $('#prompt-button').click(function() {
      // Store Facebook token as a cookie, to be used later by postToSocialMedia
      getToken('Facebook');

      // Enable the "Next" button and unhide the textarea
      $('#end-step3a-button').prop('disabled', false);
      $('#sharing-message').css( 'display', 'default' );
    });

    // Skip button
    $('#skip-button').click(function() {
      $('#end-step3a-button').prop('disabled', false);
    });

  });

  function facebookSuccess() {
    alert('Success!');
  }

  // Set "next" button up to share data from this step and set up the next
  $('#end-step3a-button').click(function() {
    // Attempt to share to Facebook (will only succeed if authorized
    var returnedRemoteLetterData = $('#persistent-data-container').data('server-response');
    var nonce = $('#persistent-data-container').data('shared-to-social-media-nonce');
    postToSocialMedia( 'Facebook', $('#sharing-message').val(), returnedRemoteLetterData, nonce );

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step4.php', function() {
        $.getScript(ajaxLocation+'js/script4.js');
    });
  });

})( jQuery );
