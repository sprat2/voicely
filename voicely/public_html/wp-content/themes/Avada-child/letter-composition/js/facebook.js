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
    getToken('Facebook');
  });

  // Callback after token retrieved
  facebookTokenCallback = function( token ) {
    // Hide the button
    $('#facebook-prompt-button').css('display', 'none');
    
    // Set the sharing message appropriately
    $('#facebook-sharing-message').val( getShareMessageWithCurrentParams() );
    
    // Unhide the textarea
    $('#facebook-sharing-message').css( 'display', 'inline' );
  }

  // Skip button
  $('#facebook-skip-button').click(function() {
    // Enable the "Next" button
    // $('#end-step3a1-button').prop('disabled', false);
  })

})( jQuery );