// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

    // XXX - Edit this when changing servers
    var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";

    // This script kicks things off with the first step: letter composition
    $('#html-display-container').load(ajaxLocation+'assets/step1.php', function() {
        $.getScript(ajaxLocation+'js/script1.js');
    });

})( jQuery );