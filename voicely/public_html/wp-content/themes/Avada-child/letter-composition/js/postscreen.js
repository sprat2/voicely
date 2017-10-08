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

    // Set up the submitted dialog's letter portion
    // sharing-icon
    $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/loading.gif">');
    // Message
    $('#letter-progress .message').html('Publishing letter');
    $('#letter-progress .message').attr('class', 'message loading');
    // $('#submit-view-container').css( 'display', 'flex' );
    $('#postscreen-overlay').css( 'display', 'block' );
    

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
      // Update the UI to indicate that the letter has been published successfully
      // sharing-icon
      $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/check.png">');
      // Message
      $('#letter-progress .message').html('Published!');
      $('#letter-progress .message').attr('class', 'message');

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
    // Alters the UI to signal letter publishing failure
    function letterFailureHandler() {
      // sharing-icon
      $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/x.png">');
      // Message
      $('#letter-progress .message').html('Failed to publish letter');
      $('#letter-progress .message').attr('class', 'message');
    }


    function shareToFacebook(returnedRemoteLetterData) {
      // Set up the sharing status display
      // sharing-icon
      $('#fb-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/loading.gif">');
      // Message
      $('#fb-share-progress .message').html('Sharing to Facebook');
      $('#fb-share-progress .message').attr('class', 'message loading');

      postToSocialMedia( 'Facebook',
        $('#tokenholder').data( 'facebook-token' ),
        $('#facebook-sharing-message').val(),
        returnedRemoteLetterData,
        shareNonce,
        function(returnedData){
          // Handle display elements for facebook success
          // sharing-icon
          $('#fb-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/check.png">');
          // Message
          $('#fb-share-progress .message').html('Shared to Facebook!');
          $('#fb-share-progress .message').attr('class', 'message');

          // Attempt to share to Twitter (will only succeed if authorized)
          shareToTwitter( returnedData );
        }, function(returnedData){
          console.log('Error sharing to Facebook'); 
          console.log(returnedData); 
          
          // Handle display elements for facebook failure
          // sharing-icon
          $('#fb-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/x.png">');
          // Message
          $('#fb-share-progress .message').html('Failed to share to Facebook');
          $('#fb-share-progress .message').attr('class', 'message');
          
          // Attempt to share to Twitter (will only succeed if authorized)
          shareToTwitter( returnedData );
        }
      );
    }


    function shareToTwitter(returnedFromFacebookSharingCall) {
      // Set up the sharing status display
      // sharing-icon
      $('#tw-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/loading.gif">');
      // Message
      $('#tw-share-progress .message').html('Sharing to Twitter');
      $('#tw-share-progress .message').attr('class', 'message loading');

      var returnedRemoteLetterData = serverResponse;
      
      postToSocialMedia( 'Twitter',
        $('#tokenholder').data( 'twitter-token' ),
        $('#twitter-sharing-message').val(),
        returnedRemoteLetterData,
        shareNonce,
        function(returnedData){
          // Handle display elements for twitter success
          // sharing-icon
          $('#tw-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/check.png">');
          // Message
          $('#tw-share-progress .message').html('Shared to Twitter!');
          $('#tw-share-progress .message').attr('class', 'message');

          proceedPastSharing();
        }, function(returnedData){
          console.log('Error sharing to Twitter'); 
          console.log(returnedData); 

          // Handle display elements for twitter failure
          // sharing-icon
          $('#tw-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'../img/x.png">');
          // Message
          $('#tw-share-progress .message').html('Failed to share to Twitter');
          $('#tw-share-progress .message').attr('class', 'message');
          
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
    }

    // Set button up to take us to our letter
    $('#end-step6-buttona').click(function() {
      window.location.href = serverResponse.url_to_letter;    
    });
  });

})( jQuery );