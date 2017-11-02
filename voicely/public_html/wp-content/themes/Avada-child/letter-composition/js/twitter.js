// Must declare here to escape the jquery wrapping's scope.
//   (This is called by another script)
function twitterTokenCallback( token ){
  console.error('function twitterTokenCallback() not defined as expected.');
};


// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';
  
  // Sharing prompt button
  $('#twitter-prompt-button').click(function() {
    $('#twitter-prompt-button').data("stepCompleted", true);
    $("#bodyInput").trigger('input'); // To reevaluate via the validation script
    getToken('Twitter');
  });

  // Callback after token retrieved
  twitterTokenCallback = function( token ) {
    // Hide the button
    // $('#twitter-prompt-button').css('display', 'none');
    
    // Set the sharing message appropriately
    $('#twitter-sharing-message').val( getShareMessageWithCurrentParams('twitter') );
    
    // Unhide the area
    $('#twitter-sharing-message-overlay-background').css( 'display', 'inline' );
  }

  // Skip button
  $('#twitter-skip-button').click(function() {
    // Enable the "Next" button
    // $('#end-step3a2-button').prop('disabled', false);
    // Remove the token (so that the user may take back their authorization)
    $('#tokenholder').removeData( 'twitter-token' );
  })

  // Hide overlay button
  $('#close-tw-overlay-button').click(function() {
    $('#twitter-sharing-message-overlay-background').css( 'display', 'none' );
  });

})( jQuery );