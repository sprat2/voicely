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
  
  // Copyover button
  $('#fb-copyover-button').click(function() {
    $('#facebook-sharing-message').val( $('#facebook-sharing-message-suggestion').val() );
  });

  // Callback after token retrieved
  facebookTokenCallback = function( token ) {
    // Hide the button
    // $('#facebook-prompt-button').css('display', 'none');
    
    // Set the sharing message appropriately
    $('#facebook-sharing-message-suggestion').val( getShareMessageWithCurrentParams('facebook') );
    
    // Unhide the area
    $('#facebook-sharing-message-overlay-background').css( 'display', 'block' );
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