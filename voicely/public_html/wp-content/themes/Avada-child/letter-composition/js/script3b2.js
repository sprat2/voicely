/* This file contains the HTML display code for step 3 - social auth - WindowsLive */

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
      getToken('WindowsLive');

      // Enable the "Next" button and unhide the textarea
      $('#select-contacts-button').prop('disabled', false);
    });

    // Contact selection button
    $('#select-contacts-button').click(function() {
      // Store token as a cookie, to be used later
      var userContacts = getContacts( 'WindowsLive', function( userContacts ) {
        // Pop up contacts display
        var htmlString = '<select id="contact-select" multiple="multiple">';
        for ( var i=0; i<userContacts.length; i++ ) {
          // Omit those without email addresses
          if ( userContacts[i].email != null ) {
            htmlString += '<option value="' + userContacts[i].email + '">' + userContacts[i].displayName + "</option>";
          }
        }
        htmlString += '</select">';
        $('#contacts-selection-div').html(htmlString);
        $('#contacts-selection-div').multiSelect();
      });

      // Enable the "Next" button and unhide the textarea
      $('#end-step3b2-button').prop('disabled', false);
    });

    // Skip button
    $('#skip-button').click(function() {
      // Enable the "Next" button
      $('#end-step3b2-button').prop('disabled', false);
    });

  });

  // Set "next" button up to share data from this step and set up the next
  $('#end-step3b2-button').click(function() {
    // Save selected contacts here so we may use them at the end
    $('#persistent-data-container').data('windowslive-selected-sharing-addresses', $('#contacts-selection-div').val());

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step4.php', function() {
        $.getScript(ajaxLocation+'js/script4.js');
    });
  });

})( jQuery );
