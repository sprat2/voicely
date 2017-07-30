/* This file contains the HTML display code for step 2 - user signin */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";

  // Set up the submitted dialog's letter portion
  // sharing-icon
  $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/loading.gif">');
  // Message
  $('#letter-progress .message').html('Publishing letter');
  $('#letter-progress .message').attr('class', 'message loading');

  submitLetter();

  // Submits the letter using the user's entered parameters from the now-completed steps
  function submitLetter() {

    // Create an array of parameters to be included in the AJAX request
    var postData = {
        addressees: $('#persistent-data-container').data('addressees'),
        title:      $('#persistent-data-container').data('title'),
        contents:   $('#persistent-data-container').data('body'),
        tags:       $('#persistent-data-container').data('tags'),
        nonce:      $('#persistent-data-container').data('post-nonce')
    };

    // Perform the AJAX request
    $.post( ajaxLocation+"assets/post.php", postData ).then(
      // Transmission success callback
      function( returnedRemoteLetterData ){

        // Access the server's response as JSON
        try {
          // Handle server-specified errors if present
          if ( returnedRemoteLetterData.error === true ) {
            alert( "We're sorry, but the server has rejected your submission." +
              "\n\nError message: " + returnedRemoteLetterData.error_msg );

            // Update UI for letter sending failure
            setTimeout(letterFailureHandler, 700);
          }
          // Else no errors - proceed
          else {
            // Process response
            setTimeout(letterSuccessHandler, 700, returnedRemoteLetterData);
          }
        }
        // Handle server response access errors
        catch ( e ) {
          alert( "JSON parse error: " + e.message + "\n\nServer response:" + data +
              "\n\nThe application has not been cleaned up properly." );

          // Update UI for letter sending failure
          setTimeout(letterFailureHandler, 700);
        }
      },
      // Transmission failure callback
      function( data ){
          alert( "Submission transmission failure." );
          console.log(data);

          // Update UI for letter sending failure
          setTimeout(letterFailureHandler, 700);
      }
    );
  }
  // Handles letter submission success
  function letterSuccessHandler( returnedRemoteLetterData ) {
    // Update the UI to indicate that the letter has been published successfully
    // sharing-icon
    $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/check.png">');
    // Message
    $('#letter-progress .message').html('Published!');
    $('#letter-progress .message').attr('class', 'message');

    // Make the call to Share to Facebook
    //postToSocialMedia( 'Facebook', returnedRemoteLetterData );

    // Save the response
    $('#persistent-data-container').data('server-response', returnedRemoteLetterData);

    // Enable the next button
    $('#end-step2b-button').prop('disabled', false);
  }
  // Alters the UI to signal letter publishing failure
  function letterFailureHandler() {
    // sharing-icon
    $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/x.png">');
    // Message
    $('#letter-progress .message').html('Failed to publish letter');
    $('#letter-progress .message').attr('class', 'message');
  }

  // Marks a post as shared
  // function markAsShared( provider, postID ) {
  //   $.get(ajaxLocation+"assets/mark-as-shared.php", { 
  //     post_id: postID, 
  //     provider: provider,
  //     nonce: $('#share-mark-nonce').val(),
  //   }).then(
  //       function( data ){
  //         // Success:
  //       },
  //       function( data ) {
  //         // Failure:
  //         console.log( data );
  //       }
  //     );
  //   }

  // Set "next" button up to store data from this step and set up the next
  $('#end-step2b-button').click(function() {
    // SAVE DATA

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step3.php', function() {
        $.getScript(ajaxLocation+'js/script3.js');
    });
  });

})( jQuery );
