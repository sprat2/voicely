// Encapsulates code, applying $ to jQuery the WP way
(function( $ ) {
  'use strict';

  // Hide the overlay when clicked
  $('#fax-hardcopy-selection-overlay').unbind('click').click( function () {
    $('#fax-hardcopy-selection-overlay').css( 'display', 'none' );
  });

  // Stop clicks on the overlay div from propagating to the overlay itself
  //   (so that they don't close the overlay)
  $('#fax-hardcopy-selection-overlay-content').unbind('click').click( function (e) {
    e.stopPropagation();
  });
    
  // Authorization button
  $('#fax-hardcopy-prompt-button').click(function() {
    $('#fax-hardcopy-prompt-button').data("stepCompleted", true);
    // $("#bodyInput").trigger('input'); // To reevaluate via the validation script
    $('#fax-hardcopy-selection-overlay').css( 'display', 'block' );
  });

})( jQuery );