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
    // Disable
    $('#facebook-prompt-button').addClass("disabled");
    $('#twitter-prompt-button').addClass("disabled");
    $('#google-prompt-button').addClass("disabled");
    $('#windowslive-prompt-button').addClass("disabled");
    $('#fax-prompt-button').addClass("disabled");
    $('#hardcopy-prompt-button').addClass("disabled");
    $('#post-now-button').prop('disabled', true);

    // Grey
    $('#facebook-prompt-button').addClass("grey");
    $('#twitter-prompt-button').addClass("grey");
    $('#google-prompt-button').addClass("grey");
    $('#windowslive-prompt-button').addClass("grey");
    $('#fax-prompt-button').addClass("grey");
    $('#hardcopy-prompt-button').addClass("grey");
    $('#post-now-button').addClass("grey");

    // Number background colors
    resetNumberBackgroundColors();
  }

  function resetNumberBackgroundColors() {
    $('#facebook-sharing-div .status-display-button:first-child').css('background-color', '#888888');
    $('#twitter-sharing-div .status-display-button:first-child').css('background-color', '#888888');
    $('#google-sharing-div .status-display-button:first-child').css('background-color', '#888888');
    $('#windowslive-sharing-div .status-display-button:first-child').css('background-color', '#888888');
    $('#fax-sharing-div .status-display-button:first-child').css('background-color', '#888888');
    $('#hardcopy-sharing-div .status-display-button:first-child').css('background-color', '#888888');
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
    var firstTicked = false;
    disableAllButtons();

    var titleIsSatisfactory = ( $('#titleInput').val().trim().length >= 3 );
    var bodyIsSatisfactory = ( $('#bodyInput').val().trim().length >= 10 );
    // var hasAddressees = ( $('#toInput').tagsinput('items').length > 0 );

    if ( titleIsSatisfactory && bodyIsSatisfactory ) {
      // Enable proper sidebar steps
      // Note: Must be done in reverse sequential order to function properly (when one enabled at a time)
      if ( sidebarStepsAreDone() ) {
        $('#post-now-button').prop('disabled', false);
        $('#post-now-button').removeClass('grey');
        firstTicked = true;
      }
      if ( $('#fax-prompt-button').data("stepCompleted") ) {
        $('#hardcopy-prompt-button').removeClass('disabled');
        $('#hardcopy-prompt-button').removeClass('grey');
        if (!firstTicked)
          $('#hardcopy-sharing-div .status-display-button:first-child').css('background-color', '#d87a68');
        firstTicked = true;
      }
      if ( $('#windowslive-prompt-button').data("stepCompleted") ) {
        $('#fax-prompt-button').removeClass('disabled');
        $('#fax-prompt-button').removeClass('grey');
        if (!firstTicked)
          $('#fax-sharing-div .status-display-button:first-child').css('background-color', '#404040');
        firstTicked = true;
      }
      if ( $('#google-prompt-button').data("stepCompleted") ) {
        $('#windowslive-prompt-button').removeClass('disabled');
        $('#windowslive-prompt-button').removeClass('grey');
        if (!firstTicked)
          $('#windowslive-sharing-div .status-display-button:first-child').css('background-color', '#2672ec');
        firstTicked = true;
      }
      if ( $('#twitter-prompt-button').data("stepCompleted") ) {
        $('#google-prompt-button').removeClass('disabled');
        $('#google-prompt-button').removeClass('grey');
        if (!firstTicked)
          $('#google-sharing-div .status-display-button:first-child').css('background-color', '#dd4b39');
        firstTicked = true;
      }
      if ( $('#facebook-prompt-button').data("stepCompleted") ) {
        $('#twitter-prompt-button').removeClass('disabled');
        $('#twitter-prompt-button').removeClass('grey');
        if (!firstTicked)
          $('#twitter-sharing-div .status-display-button:first-child').css('background-color', '#55acee');
        firstTicked = true;
      }
      $('#facebook-prompt-button').removeClass('disabled');
      $('#facebook-prompt-button').removeClass('grey');
      if (!firstTicked)
        $('#facebook-sharing-div .status-display-button:first-child').css('background-color', '#3b5998');
      firstTicked = true;
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
      tooltipText = "Feel free to address your letter to specific people";
    
    if ( tooltipText == "Tooltip error" ) {
      // No messages to display - disable the tooltip for now
      $('#publish-button-div .tooltiptext').html("Tooltip should be disabled");
      $('#publish-button-div .tooltiptext').removeClass("display-enabled");
    }
    else {
      // change tooltip text here and make sure it's enabled
      $('#publish-button-div .tooltiptext').html(tooltipText);
      $('#publish-button-div .tooltiptext').addClass("display-enabled");
    }
  }

  $(document).ready(function() {
    // Check to set the initial state of the button
    enabledStateUpdate();
    reevaluateTooltipText();
  });

})( jQuery );