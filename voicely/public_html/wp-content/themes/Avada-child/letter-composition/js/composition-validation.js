var saveInterval = null;

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // Sidebar button states
  disableAllByDefault();

  function disableAllByDefault() {
    $('#facebook-prompt-button').data("stepCompleted", false);
    $('#twitter-prompt-button').data("stepCompleted", false);
    $('#google-prompt-button').data("stepCompleted", false);
    $('#windowslive-prompt-button').data("stepCompleted", false);
    $('#fax-prompt-button').data("stepCompleted", false);
    $('#hardcopy-prompt-button').data("stepCompleted", false);
    disableAllButtons();
  }

  function disableAllButtons() {
    $('#facebook-prompt-button').addClass("disabled");
    $('#twitter-prompt-button').addClass("disabled");
    $('#google-prompt-button').addClass("disabled");
    $('#windowslive-prompt-button').addClass("disabled");
    $('#fax-prompt-button').addClass("disabled");
    $('#hardcopy-prompt-button').addClass("disabled");
    $('#post-now-button').prop('disabled', true);
  }

  function sidebarStepsAreDone() {
    return $('#facebook-prompt-button').data("stepCompleted") &&
      $('#twitter-prompt-button').data("stepCompleted") &&
      $('#google-prompt-button').data("stepCompleted") &&
      $('#windowslive-prompt-button').data("stepCompleted") &&
      $('#fax-prompt-button').data("stepCompleted") &&
      $('#hardcopy-prompt-button').data("stepCompleted");
  }

  // Register listeners
  $("#titleInput").on("input", function() { enabledStateUpdate(); });
  $("#bodyInput").on("input", function() { enabledStateUpdate(); });
  $("#toInput").on('itemAdded', reevaluateTooltipText);
  $("#toInput").on('itemRemoved', reevaluateTooltipText);
  $("#post-now-button").on('hover', reevaluateTooltipText);
  $("#titleInput").on("input", function() { reevaluateTooltipText(); });
  $("#bodyInput").on("input", function() { reevaluateTooltipText(); });
  
  // Checks if the letter is publishable and disables/enables the Publish button accordingly
  function enabledStateUpdate() {
    disableAllButtons();

    var titleIsSatisfactory = ( $('#titleInput').val().trim().length >= 3 );
    var bodyIsSatisfactory = ( $('#bodyInput').val().trim().length >= 10 );
    // var hasAddressees = ( $('#toInput').tagsinput('items').length > 0 );

    if ( titleIsSatisfactory && bodyIsSatisfactory ) {
      // Enable proper sidebar step
      // Note: Must be done in reverse sequential order to function properly
      if ( sidebarStepsAreDone() )
        $('#post-now-button').prop('disabled', false);
      else if ( $('#fax-prompt-button').data("stepCompleted") )
        $('#hardcopy-prompt-button').removeClass('disabled');
      else if ( $('#windowslive-prompt-button').data("stepCompleted") )
        $('#fax-prompt-button').removeClass('disabled');
      else if ( $('#google-prompt-button').data("stepCompleted") )
        $('#windowslive-prompt-button').removeClass('disabled');
      else if ( $('#twitter-prompt-button').data("stepCompleted") )
        $('#google-prompt-button').removeClass('disabled');
      else if ( $('#facebook-prompt-button').data("stepCompleted") )
        $('#twitter-prompt-button').removeClass('disabled');
      else
        $('#facebook-prompt-button').removeClass('disabled');
    }
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
    else if (!sidebarStepsAreDone())
      tooltipText = "Please complete the steps above";
    else if (!hasAddressees)
      tooltipText = "If you don't choose any recipients your letter will be addressed to \"The World\"";
    
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
    enabledStateUpdate();
    reevaluateTooltipText();
  });

})( jQuery );