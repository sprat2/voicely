// Must declare here to escape the jquery wrapping's scope.
//   (This is called by another script)
function loadGoogleContacts( token ){
  console.error('function loadGoogleContacts() not defined as expected.');
};

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // Authorization button
  $('#google-prompt-button').click(function() {
    // Store google contacts as a cookie, to be used by selectContacts
    getToken('Google');
  });

  // Contact selection
  loadGoogleContacts = function( token ) {
    // Hide the authentication button now that we've been granted access
    $('#google-prompt-button').css( 'display', 'none' );

    // Get their contacts and display them appropriately
    $('#google-contacts-selection-div').html('Loading contacts...');
    var userContacts = getContacts( 'Google', token, function( userContacts ) {

      // Instantiate array of selected contacts
      jQuery('#google-contacts-selection-div').data( 'selected-sharing-addresses', [] );
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
      $('#google-contacts-selection-div').html(htmlString);
      $('#google-contacts-selection-div').multiSelect({
        afterSelect: function(values){
          addSelectedEmail(values[0]);
        },
        afterDeselect: function(values){
          removeSelectedEmail(values[0]);
        }
      });
    });
    
    // Enable the "Next" button and unhide the textarea
    // $('#end-step3b1-button').prop('disabled', false);
  }

  // Skip button
  $('#skip-button').click(function() {
    // Enable the "Next" button
    // $('#end-step3b1-button').prop('disabled', false);
  });


  // Functions to add and remove emails from the selection (fired when selected/deselected)
  function addSelectedEmail(email) {
    var existing = jQuery('#google-contacts-selection-div').data( 'selected-sharing-addresses' );
    existing.push(email);
    jQuery('#google-contacts-selection-div').data( 'selected-sharing-addresses', existing );
  }
  function removeSelectedEmail(email) {
    var existing = jQuery('#google-contacts-selection-div').data( 'selected-sharing-addresses' );
    var i = existing.indexOf(email);
    if(i != -1)
      existing.splice(i, 1);
    jQuery('#google-contacts-selection-div').data( 'selected-sharing-addresses', existing );  }

})( jQuery );