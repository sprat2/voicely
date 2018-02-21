// Must declare here to escape the jquery wrapping's scope.
//   (This is called by another script)
function facebookTokenCallback( token ){
  console.error('function facebookTokenCallback() not defined as expected.');
};


// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';
  
  // Sharing prompt button
  $('#facebook-prompt-button').click(function() {
    $('#facebook-prompt-button').data("stepCompleted", true);
    $("#bodyInput").trigger('input'); // To reevaluate via the validation script
    getToken('Facebook');
  });

  // User opted out.  Flush their sharing token and close the overlay.
  $('#skip-fb-overlay-text').click(function() {
    $('#tokenholder').removeData( 'facebook-token' );
    $('#facebook-sharing-message-overlay-background').css( 'display', 'none' );
  });
  
  // Copyover button
  $('#fb-copyover-button').click(function() {
    $('#facebook-sharing-message').val( $('#facebook-sharing-message-suggestion').val() );
    
    // Update remaining character count
    $('#facebook-sharing-message').trigger('propertychange');
  });

  // On message change...
  $('#facebook-sharing-message').bind('input propertychange', function() {
    var maxChars = 280;

    // Enable the share button if there's a message - else, disable it again
    if ( ( $('#facebook-sharing-message').val().length == 0 ) || 
         ( $('#facebook-sharing-message').val().length > maxChars ) )
      $('#close-fb-overlay-button').prop("disabled", true);
    else
      $('#close-fb-overlay-button').prop("disabled", false);

    // Update the "characters remaining" message
    var remainingCharacters = maxChars - $('#facebook-sharing-message').val().length;
    $('#facebook-character-limit-message i').text( remainingCharacters );
  });

  // Callback after token retrieved
  facebookTokenCallback = function( token ) {
    // Hide the button
    // $('#facebook-prompt-button').css('display', 'none');
    
    // Set the sharing message appropriately
    $('#facebook-sharing-message-suggestion').val( getShareMessageWithCurrentParams('facebook') );
    
    // Unhide the area
    $('#facebook-sharing-message-overlay-background').css( 'display', 'block' );

    // Update remaining character count
    $('#facebook-sharing-message').trigger('propertychange');
  }

  // Hide overlay button
  $('#close-fb-overlay-button').click(function() {
    $('#facebook-sharing-message-overlay-background').css( 'display', 'none' );
  });

  // Skip button
  $('#facebook-skip-button').click(function() {
    // Enable the "Next" button
    // $('#end-step3a1-button').prop('disabled', false);
    // Remove the token (so that the user may take back their authorization)
    $('#tokenholder').removeData( 'facebook-token' );
  })

})( jQuery );