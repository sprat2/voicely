// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';
  
  // Callback after token retrieved
  $('#fax-prompt-button').click(function() {
    $('#fax-prompt-button').data("stepCompleted", true);
    $("#bodyInput").trigger('input'); // To reevaluate via the validation script
  });

  // // Skip button
  // $('#fax-skip-button').click(function() {
  //   // Gray out & disable the button
  //   $('#fax-prompt-button').css('opacity', '0.5');
  //   $('#fax-prompt-button').addClass('disabled');
  // })
    
  // Callback after token retrieved
  $('#hardcopy-prompt-button').click(function() {
    $('#hardcopy-prompt-button').data("stepCompleted", true);
    $("#bodyInput").trigger('input'); // To reevaluate via the validation script
  });

  // // Skip button
  // $('#hardcopy-skip-button').click(function() {
  //   // Gray out & disable the button
  //   $('#hardcopy-prompt-button').css('opacity', '0.5');
  //   $('#hardcopy-prompt-button').addClass('disabled');
  // })

})( jQuery );