// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // Callback after token retrieved
  $('#fax-hardcopy-prompt-button').click(function() {
    // Gray out & disable the button
    $('#fax-hardcopy-prompt-button').css('opacity', '0.5');
    $('#fax-hardcopy-prompt-button').addClass('disabled');
  });

  // Skip button
  $('#fax-hardcopy-skip-button').click(function() {
    // Gray out & disable the button
    $('#fax-hardcopy-prompt-button').css('opacity', '0.5');
    $('#fax-hardcopy-prompt-button').addClass('disabled');
  })

})( jQuery );