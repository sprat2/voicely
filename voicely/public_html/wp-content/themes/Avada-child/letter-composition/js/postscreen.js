// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // Elevate the scope of certain variables we'll use throughout this script
  var serverResponse = null;
  var selectedAddressees = null;
  var title = null;
  var body = null;
  var tags = null;
  var postNonce = null;
  var shareNonce = null;

  // Hide the overlay background when clicked
  // $('#postscreen-overlay').unbind('click').click( function () {
  //   $('#postscreen-overlay').css( 'display', 'none' );    
  // });

  // Handle click event for the Publish button, removing previous event handlers to avoid a duplication bug
  $('#post-now-button').unbind('click').click( function () {
    // Lock the inputs so that the user can't edit them anymore
    $('#titleInput').prop('disabled', true);
    $('#bodyInput').prop('disabled', true);
    $('#tagsInput').prop('disabled', true);
    $('#toInput').prop('disabled', true);
    $('#facebook-sharing-message').prop('disabled', true);
    $('#twitter-sharing-message').prop('disabled', true);
    $('#facebook-prompt-button').addClass('disabled');
    $('#twitter-prompt-button').addClass('disabled');
    $('#google-prompt-button').addClass('disabled');
    $('#windowslive-prompt-button').addClass('disabled');
    $('#fax-prompt-button').addClass('disabled');
    $('#hardcopy-prompt-button').addClass('disabled');
    
    // Gather the inputs
    selectedAddressees = $('#toInput').tagsinput('items');
    selectedAddressees = selectedAddressees.map( function(element){ return element.twitter_handle; });
    title = $('#titleInput').val();
    body = $('#bodyInput').val();
    tags = $('#tagsInput').tagsinput('items');
    postNonce = $('#nonceholder').data( 'post-nonce' );
    shareNonce = $('#nonceholder').data( 'shared-to-social-media-nonce' );
    // var shareMarkNonce = $('#nonceholder').data( 'share-mark-nonce' );
    // var googleContactsNonce = $('#nonceholder').data( 'google-contacts-nonce' );

    // $('#postscreen-overlay').css( 'display', 'block' );

    // Start the letter submitting process
    submitLetter();
    
    // Submits the letter using the user's entered parameters from the now-completed steps
    function submitLetter() {
      // Create an array of parameters to be included in the AJAX request
      var postData = {
        addressees: selectedAddressees,
        title:      title,
        contents:   body,
        tags:       tags,
        nonce:      postNonce,
      };
      // Perform the AJAX request
      $.post( ajaxLocation+"post.php", postData ).then(
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
              // setTimeout(letterSuccessHandler, 700, returnedRemoteLetterData);
              letterSuccessHandler( returnedRemoteLetterData );
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
          alert( "Submission transmission failure in submitLetter()." );
          console.log(data);

          // Update UI for letter sending failure
          setTimeout(letterFailureHandler, 700);
        }
      );
    }


    // Handles letter submission success
    function letterSuccessHandler( returnedRemoteLetterData ) {

      // Save the response
      serverResponse = returnedRemoteLetterData;

      // Stop saving and delete the letter progress cookies
      if ( saveInterval != null )
        clearInterval( window.saveInterval );
      else
        console.error("Unable to stop letter progress saving.");
      document.cookie = 'savedTitle=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = 'savedTags=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = 'savedAddressees=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      document.cookie = 'savedLetter=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';

      // Enable the letter URL button
      $('#end-step6-buttona').prop("disabled", false);
      
      // Make the calls to share to social media
      // Attempt to share to Facebook (will only succeed if authorized)
      shareToFacebook(returnedRemoteLetterData);
    }

    function shareToFacebook(returnedRemoteLetterData) {
      // Set up the sharing status display
      // Update status 
      $('#fb-status-indicator').html('…');
      $('#fb-status-indicator').css('color', 'white');
      $('#facebook-prompt-button .social-prompt-text').text('Sharing to Facebook...');

      postToSocialMedia( 'Facebook',
        $('#tokenholder').data( 'facebook-token' ),
        $('#facebook-sharing-message').val(),
        returnedRemoteLetterData,
        shareNonce,
        function(returnedData){
          // On a delay...
          setTimeout(function() {
            // Handle display elements for facebook success
            // Update status icon
            $('#fb-status-indicator').html('✓');
            $('#fb-status-indicator').css('color', 'lightgreen');
            $('#facebook-prompt-button .social-prompt-text').text('Shared to Facebook!');
  
            // Attempt to share to Twitter (will only succeed if authorized)
            shareToTwitter( returnedData );
          }, 350);
        }, function(returnedData){
          console.log('Error sharing to Facebook'); 
          console.log(returnedData); 
          
          // Handle display elements for facebook failure  
          // Update status icon
          $('#fb-status-indicator').html('🗙');
          $('#fb-status-indicator').css('color', 'red');
          $('#facebook-prompt-button .social-prompt-text').text('Not shared to Facebook');
          
          // If we failed due to improper authorization, show a yellow dash instead
          if ( ( returnedData.error_msg.indexOf('Invalid or expired token') !== -1 ) ||
               ( returnedData.error_msg.indexOf('Token not set') !== -1 ) ) {
            $('#fb-status-indicator').html('–');
            $('#fb-status-indicator').css('color', 'yellow');
            $('#facebook-prompt-button .social-prompt-text').text('Not shared to Facebook');
          }
          
          // Attempt to share to Twitter (will only succeed if authorized)
          shareToTwitter( returnedData );
        }
      );
    }


    function shareToTwitter(returnedFromFacebookSharingCall) {
      // Set up the sharing status display
      // Update status 
      $('#tw-status-indicator').html('…');
      $('#tw-status-indicator').css('color', 'white');
      $('#twitter-prompt-button .social-prompt-text').text('Sharing to Twitter...');

      var returnedRemoteLetterData = serverResponse;
      
      postToSocialMedia( 'Twitter',
        $('#tokenholder').data( 'twitter-token' ),
        $('#twitter-sharing-message').val(),
        returnedRemoteLetterData,
        shareNonce,
        function(returnedData){
          // On a delay...
          setTimeout(function() {
            // Handle display elements for twitter success
            // Update status icon
            $('#tw-status-indicator').html('✓');
            $('#tw-status-indicator').css('color', 'lightgreen');
            $('#twitter-prompt-button .social-prompt-text').text('Shared to Twitter!');

            proceedPastSharing();
          }, 350);
        }, function(returnedData){
          console.log('Error sharing to Twitter'); 
          console.log(returnedData); 

          // Handle display elements for twitter failure
          // Update status icon
          $('#tw-status-indicator').html('🗙');
          $('#tw-status-indicator').css('color', 'red');
          $('#twitter-prompt-button .social-prompt-text').text('Not shared to Twitter');
          
          // If we failed due to improper authorization, show a yellow dash instead
          if ( ( returnedData.error_msg.indexOf('Invalid or expired token') !== -1 ) ||
               ( returnedData.error_msg.indexOf('Token not set') !== -1 ) ) {
            $('#tw-status-indicator').html('–');
            $('#tw-status-indicator').css('color', 'yellow');
            $('#twitter-prompt-button .social-prompt-text').text('Not shared to Twitter');
          }

          proceedPastSharing();
        }
      );
    }


    function proceedPastSharing() {
      // Email mailto: prompt
      // Recipients
      var recipients = $('#google-contacts-selection-div').data( 'selected-sharing-addresses' ) || [];
      recipients = recipients.concat( $('#windowslive-contacts-selection-div').data( 'selected-sharing-addresses' ) );
      recipients = encodeURI( recipients.join(',') );
      if ( recipients == 0 ) recipients = [];
      // Subject
      var subject = encodeURI( getShareMessageWithCurrentParams() );
      // Body
      var body = encodeURI( 'Come check it out at ' + serverResponse.url_to_letter );
      $('#mailto-link').html('<a href="mailto:'+recipients+'?subject='+subject+'&body='+body+'">Email your contacts!</a>');

      // TODO: URL link display
      //$('#url-to-clipboard').html('Clipboard-copiable link to go here:<br><a href="' + serverResponse.url_to_letter + '>your letter</a>.');
      
      faxPublishingUIUpdate();
    }
    
    function faxPublishingUIUpdate() {
      // Update status UI
      $('#fax-sharing-div .updatable-status').html('…');
      $('#fax-sharing-div .updatable-status').css('color', 'white');
      $('#fax-sharing-div .social-prompt-text').text('Faxing...');

      // On a delay...
      setTimeout(function() {
        // Update the UI to indicate that the letter has been published successfully
        $('#fax-sharing-div .updatable-status').html('✓');
        $('#fax-sharing-div .updatable-status').css('color', 'lightgreen');
        $('#fax-sharing-div .social-prompt-text').text('Faxed!');

        hardcopyPublishingUIUpdate();
      }, 700);
    }
    
    function hardcopyPublishingUIUpdate() {
      // Update status UI
      $('#hardcopy-sharing-div .updatable-status').html('…');
      $('#hardcopy-sharing-div .updatable-status').css('color', 'white');
      $('#hardcopy-sharing-div .social-prompt-text').text('Mailing...');

      // On a delay...
      setTimeout(function() {
        // Update the UI to indicate that the letter has been published successfully
        $('#hardcopy-sharing-div .updatable-status').html('✓');
        $('#hardcopy-sharing-div .updatable-status').css('color', 'lightgreen');
        $('#hardcopy-sharing-div .social-prompt-text').text('Mailed!');

        letterPublishingUIUpdate();
      }, 1000);
    }

    function letterPublishingUIUpdate() {
      // Update status UI
      $('#publish-button-div .status-display-button').html('…');
      $('#publish-button-div .status-display-button').css('color', 'white');
      $('#post-now-button').text('Publishing...');

      // On a delay...
      setTimeout(function() {
        // Update the UI to indicate that the letter has been published successfully
        $('#publish-button-div .status-display-button').html('✓');
        $('#publish-button-div .status-display-button').css('color', 'lightgreen');
        $('#post-now-button').text('Published!');

        // Display links (temporarily disabled)
        // $('#postscreen-buttons-div').css( 'display', 'block' );

        // Redirect on a delay...
        setTimeout(function() {
          window.location.href = serverResponse.url_to_letter; 
        }, 0);
      }, 700);
    }
    // Alters the UI to signal letter publishing failure
    function letterFailureHandler() {
      // Update status 
      $('#publish-button-div .status-display-button').html('🗙');
      $('#publish-button-div .status-display-button').css('color', 'red');
      $('#post-now-button').text('Not Published');
    }

    // Set button up to take us to our letter
    $('#end-step6-buttona').click(function() {
      window.location.href = serverResponse.url_to_letter;    
    });
  });

})( jQuery );