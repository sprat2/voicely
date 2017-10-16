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
    $('#facebook-prompt-button').removeClass('button-clicked');
    $('#facebook-prompt-button').addClass('disabled');
    $('#twitter-prompt-button').removeClass('disabled');
    getToken('Facebook');
  });
  
  // Copyover button
  $('#fb-copyover-button').click(function() {
    $('#facebook-sharing-message').val( $('#facebook-sharing-message-suggestion').val() );
  })

  // Callback after token retrieved
  facebookTokenCallback = function( token ) {
    // Hide the button
    // $('#facebook-prompt-button').css('display', 'none');
    // Gray out & disable the button
    $('#facebook-prompt-button').css('opacity', '0.5');
    $('#facebook-prompt-button').addClass('disabled');
    
    // Set the sharing message appropriately
    $('#facebook-sharing-message-suggestion').val( getShareMessageWithCurrentParams() );
    
    // Unhide the textarea
    $('#facebook-sharing-message-content').css( 'display', 'block' );
  }

  // Skip button
  $('#facebook-skip-button').click(function() {
    // Enable the "Next" button
    // $('#end-step3a1-button').prop('disabled', false);
    // Gray out & disable the button
    $('#facebook-prompt-button').css('opacity', '0.5');
    $('#facebook-prompt-button').addClass('disabled');
    // Remove the token (so that the user may take back their authorization)
    $('#tokenholder').removeData( 'facebook-token' );
  })

})( jQuery );