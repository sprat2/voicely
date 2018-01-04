// Encapsulates code, applying $ to jQuery the WP way
(function( $ ) {
  'use strict';

  // Hide the overlay when clicked
  $('#email-contacts-selection-overlay').unbind('click').click( function () {
    $('#email-contacts-selection-overlay').css( 'display', 'none' );
  });

  // Stop clicks on the overlay div from propagating to the overlay itself
  //   (so that they don't close the overlay)
  $('#email-contacts-selection-overlay-content').unbind('click').click( function (e) {
    e.stopPropagation();
  });
    
  // Authorization button
  $('#email-contacts-prompt-button').click(function() {
    $('#email-contacts-prompt-button').data("stepCompleted", true);
    // $("#bodyInput").trigger('input'); // To reevaluate via the validation script
    $('#email-contacts-selection-overlay').css( 'display', 'block' );
  });

})( jQuery );