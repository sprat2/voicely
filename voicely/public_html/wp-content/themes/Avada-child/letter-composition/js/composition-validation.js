var saveInterval = null;

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // Register listeners
  $("#titleInput").on("input", function() { publishableCheck(); });
  $("#bodyInput").on("input", function() { publishableCheck(); });
  $("#toInput").on('itemAdded', reevaluateTooltipText);
  $("#toInput").on('itemRemoved', reevaluateTooltipText);
  $("#titleInput").on("input", function() { reevaluateTooltipText(); });
  $("#bodyInput").on("input", function() { reevaluateTooltipText(); });
  
  // Checks if the letter is publishable and disables/enables the Publish button accordingly
  function publishableCheck() {
    var titleIsSatisfactory = ( $('#titleInput').val().trim().length >= 3 );
    var bodyIsSatisfactory = ( $('#bodyInput').val().trim().length >= 10 );
    // var hasAddressees = ( $('#toInput').tagsinput('items').length > 0 );

    if ( titleIsSatisfactory && bodyIsSatisfactory )
      $('#post-now-button').prop('disabled', false);
    else
      $('#post-now-button').prop('disabled', true);
  }

  // Determines tooltip text when user hovers over the Publish button
  function reevaluateTooltipText() {
    var titleIsSatisfactory = ( $('#titleInput').val().trim().length >= 3 );
    var bodyIsSatisfactory = ( $('#bodyInput').val().trim().length >= 10 );
    var hasAddressees = ( $('#toInput').tagsinput('items').length > 0 );

    var tooltipText = "Tooltip error";
    if ( !titleIsSatisfactory && !bodyIsSatisfactory )
      tooltipText = "Please write your letter before trying to publish it";
    else if (!titleIsSatisfactory)
      tooltipText = "Please choose a longer title";
    else if (!bodyIsSatisfactory)
      tooltipText = "Please write a longer letter";
    else if (!hasAddressees) {
      tooltipText = "If you don't choose any recipients your letter will be addressed to \"The World\"";
    }

    if ( tooltipText == "Tooltip error" ) {
      // No messages to display - disable the tooltip for now
      $('#tooltiptext').html("Tooltip should be disabled");
      $('#tooltiptext').removeClass("display-enabled");
    }
    else {
      // change tooltip text here and make sure it's enabled
      $('#tooltiptext').html(tooltipText);
      $('#tooltiptext').addClass("display-enabled");
    }
  }

  $(document).ready(function() {
    // Check to set the initial state of the button
    publishableCheck();
    reevaluateTooltipText();
  });

})( jQuery );