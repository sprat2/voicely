/* This file contains the HTML display code for step 5 - Money collection */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
var detS = "";
if (location.protocol == 'https:') detS = "s";
var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/";



  // Set "next" button up to store data from this step and set up the next
  $('#end-step5-button').click(function() {
    // SAVE DATA

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step6.php', function() {
        $.getScript(ajaxLocation+'js/script6.js');
    });
  });

})( jQuery );
