/* This file contains the HTML display code for step 2 - user signin */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";




  // Set "next" button up to store data from this step and set up the next
  $('#end-step2a-button').click(function() {
    // SAVE DATA

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step2b.php', function() {
        $.getScript(ajaxLocation+'js/script2b.js');
    });
  });

})( jQuery );
