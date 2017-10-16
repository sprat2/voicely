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
    $('#twitter-prompt-button').addClass('disabled');
    $('#google-prompt-button').removeClass('disabled');
    getToken('Twitter');
  });

  // Callback after token retrieved
  twitterTokenCallback = function( token ) {
    // Hide the button
    // $('#twitter-prompt-button').css('display', 'none');
    // Gray out & disable the button
    $('#twitter-prompt-button').css('opacity', '0.5');
    $('#twitter-prompt-button').addClass('disabled');
    
    // Set the sharing message appropriately
    $('#twitter-sharing-message').val( getShareMessageWithCurrentParams() );
    
    // Unhide the textarea
    $('#twitter-sharing-message').css( 'display', 'inline' );
  }

  // Skip button
  $('#twitter-skip-button').click(function() {
    // Enable the "Next" button
    // $('#end-step3a2-button').prop('disabled', false);
    // Gray out & disable the button
    $('#twitter-prompt-button').css('opacity', '0.5');
    $('#twitter-prompt-button').addClass('disabled');
    // Remove the token (so that the user may take back their authorization)
    $('#tokenholder').removeData( 'twitter-token' );
  })

})( jQuery );