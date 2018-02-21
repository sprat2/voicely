// Encapsulates code, applying $ to jQuery the WP way
(function( $ ) {
  'use strict';

  // Hide the overlay when clicked
  $('#social-networks-selection-overlay').unbind('click').click( function () {
    $('#social-networks-selection-overlay').css( 'display', 'none' );
  });

  // Stop clicks on the overlay div from propagating to the overlay itself
  //   (so that they don't close the overlay)
  $('#social-networks-selection-overlay-content').unbind('click').click( function (e) {
    e.stopPropagation();
  });
    
  // Authorization button
  $('#social-networks-prompt-button').click(function() {
    $('#social-networks-prompt-button').data("stepCompleted", true);
    // $("#bodyInput").trigger('input'); // To reevaluate via the validation script
    $('#social-networks-selection-overlay').css( 'display', 'block' );
  });

})( jQuery );