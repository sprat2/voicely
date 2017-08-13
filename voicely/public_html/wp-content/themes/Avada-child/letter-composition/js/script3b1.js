/* This file contains the HTML display code for step 3 - social auth - Gmail */

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

    // Authorization button
    $('#prompt-button').click(function() {
      // Store Gmail contacts as a cookie, to be used by selectContacts
      getToken('Google');

      // Enable the "Next" button and unhide the textarea
      $('#select-contacts-button').prop('disabled', false);
    });

    // Contact selection button
    $('#select-contacts-button').click(function() {
      // Store Gmail token as a cookie, to be used later
      var userContacts = getContacts( 'Google', function( userContacts ) { 
        console.log(userContacts);
      });

      // Enable the "Next" button and unhide the textarea
      $('#end-step3b1-button').prop('disabled', false);
    });

    // Skip button
    $('#skip-button').click(function() {
      // Enable the "Next" button
      $('#end-step3b1-button').prop('disabled', false);
    });

  });

  // Set "next" button up to share data from this step and set up the next
  $('#end-step3b1-button').click(function() {
    // Save selected contacts here so we may use them at the end
    //$('#persistent-data-container').data('gmail-selected-sharing-addresses', XXXX);

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step3b2.php', function() {
        $.getScript(ajaxLocation+'js/script3b2.js');
    });
  });

})( jQuery );
