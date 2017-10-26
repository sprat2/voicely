// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';
  
  // Callback after token retrieved
  $('#fax-prompt-button').click(function() {
    $('#fax-prompt-button').addClass('disabled');
    $('#fax-prompt-button').data("stepCompleted", true);
    $('#hardcopy-prompt-button').removeClass('disabled');
  });

  // // Skip button
  // $('#fax-skip-button').click(function() {
  //   // Gray out & disable the button
  //   $('#fax-prompt-button').css('opacity', '0.5');
  //   $('#fax-prompt-button').addClass('disabled');
  // })
    
  // Callback after token retrieved
  $('#hardcopy-prompt-button').click(function() {
    $('#hardcopy-prompt-button').addClass('disabled');
    $('#hardcopy-prompt-button').data("stepCompleted", true);
    // Enable "Publish"
    $('#post-now-button').prop('disabled', false);
  });

  // // Skip button
  // $('#hardcopy-skip-button').click(function() {
  //   // Gray out & disable the button
  //   $('#hardcopy-prompt-button').css('opacity', '0.5');
  //   $('#hardcopy-prompt-button').addClass('disabled');
  // })

})( jQuery );