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
        console.log( userContacts );

        // Instantiate selected array
        $('#persistent-data-container').data('windowslive-selected-sharing-addresses', []);
        // Pop up contacts display
        var htmlString = '<select id="contact-select" multiple="multiple">';
        for ( var i=0; i<userContacts.length; i++ ) {
          // Omit those without email addresses
          if ( ( userContacts[i].email != null ) && ( userContacts[i].email.trim() != "" ) ) {
            // If name is empty, insert email address in its place
            if ( !userContacts[i].displayName.trim() ) {
              userContacts[i].displayName = userContacts[i].email;
            }
            // Create the element for this entry
            htmlString += '<option value="' + userContacts[i].email + '">' + userContacts[i].displayName + "</option>";
          }
        }
        htmlString += '</select">';
        $('#windowslive-contacts-selection-div').html(htmlString);
        $('#windowslive-contacts-selection-div').multiSelect({
          afterSelect: function(values){
            addSelectedEmail(values[0]);
          },
          afterDeselect: function(values){
            removeSelectedEmail(values[0]);
          }
        });
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

  // Functions to add and remove emails from the selection (fired when selected/deselected)
  function addSelectedEmail(email) {
    var existing = $('#persistent-data-container').data('windowslive-selected-sharing-addresses');
    existing.push(email);
    $('#persistent-data-container').data('windowslive-selected-sharing-addresses', existing);
  }
  function removeSelectedEmail(email) {
    var existing = $('#persistent-data-container').data('windowslive-selected-sharing-addresses');
    var i = existing.indexOf(email);
    if(i != -1)
      existing.splice(i, 1);
    $('#persistent-data-container').data('windowslive-selected-sharing-addresses', existing);
  }

  // Set "next" button up to share data from this step and set up the next
  $('#end-step3b2-button').click(function() {
    
    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step4.php', function() {
        $.getScript(ajaxLocation+'js/script4.js');
    });
  });

})( jQuery );
