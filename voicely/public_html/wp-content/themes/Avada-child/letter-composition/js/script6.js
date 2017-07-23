/* This file contains the HTML display code for step 2 - user signin */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";




  // Set "next" button up to store data from this step and set up the next
  $('#end-step6-button').click(function() {
    // SAVE DATA

    window.location.href = '/'; // XXX: Replace this with the URL of the published letter
  });

})( jQuery );
