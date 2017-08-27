/* This file contains the HTML display code for step 2 - user signin */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var detS = "";
  if (location.protocol == 'https:') detS = "s";
  var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/";

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
    $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/check.png">');
    // Message
    $('#letter-progress .message').html('Published!');
    $('#letter-progress .message').attr('class', 'message');

    // Save the response
    $('#persistent-data-container').data('server-response', returnedRemoteLetterData);

    // Delete the saved letter body cookie
    document.cookie = 'savedLetter=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';

    // Make the calls to share to social media
    // Attempt to share to Facebook (will only succeed if authorized)
    shareToFacebook(returnedRemoteLetterData);
  }
  // Alters the UI to signal letter publishing failure
  function letterFailureHandler() {
    // sharing-icon
    $('#letter-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/x.png">');
    // Message
    $('#letter-progress .message').html('Failed to publish letter');
    $('#letter-progress .message').attr('class', 'message');
  }


  function shareToFacebook(returnedRemoteLetterData) {
    // Set up the sharing status display
    // sharing-icon
    $('#fb-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/loading.gif">');
    // Message
    $('#fb-share-progress .message').html('Sharing to Facebook...');
    $('#fb-share-progress .message').attr('class', 'message loading');

    var nonce = $('#persistent-data-container').data('shared-to-social-media-nonce');
    postToSocialMedia( 'Facebook', $('#persistent-data-container').data('fb-sharing-message'), returnedRemoteLetterData, nonce, function(returnedData){
      // Handle display elements for facebook success
      // sharing-icon
      $('#fb-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/check.png">');
      // Message
      $('#fb-share-progress .message').html('Shared to Facebook!');
      $('#fb-share-progress .message').attr('class', 'message');

      // Attempt to share to Twitter (will only succeed if authorized)
      shareToTwitter(returnedData);
    }, function(returnedData){
      console.log('Error sharing to Facebook'); 
      console.log(returnedData); 
      
      // Handle display elements for facebook failure
      // sharing-icon
      $('#fb-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/x.png">');
      // Message
      $('#fb-share-progress .message').html('Failed to share to Facebook');
      $('#fb-share-progress .message').attr('class', 'message');
      
      // Attempt to share to Twitter (will only succeed if authorized)
      shareToTwitter(returnedData);
    });
  }


  function shareToTwitter(returnedFromFacebookSharingCall) {
    // Set up the sharing status display
    // sharing-icon
    $('#tw-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/loading.gif">');
    // Message
    $('#tw-share-progress .message').html('Sharing to Twitter...');
    $('#tw-share-progress .message').attr('class', 'message loading');

    var returnedRemoteLetterData = $('#persistent-data-container').data('server-response');
    var nonce = $('#persistent-data-container').data('shared-to-social-media-nonce');
    postToSocialMedia( 'Twitter', $('#persistent-data-container').data('tw-sharing-message'), returnedRemoteLetterData, nonce, function(returnedData){
      // Handle display elements for twitter success
      // sharing-icon
      $('#tw-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/check.png">');
      // Message
      $('#tw-share-progress .message').html('Shared to Twitter!');
      $('#tw-share-progress .message').attr('class', 'message');

      proceedPastSharing();
    }, function(returnedData){
      console.log('Error sharing to Twitter'); 
      console.log(returnedData); 

      // Handle display elements for twitter failure
      // sharing-icon
      $('#tw-share-progress .sharing-icon').html('<img src="'+ajaxLocation+'img/x.png">');
      // Message
      $('#tw-share-progress .message').html('Failed to share to Twitter');
      $('#tw-share-progress .message').attr('class', 'message');
      
      proceedPastSharing();
    });
  }


  function proceedPastSharing() {
    // Email mailto: prompt
      $.getScript(ajaxLocation+"js/social-sharing.js", function(){
      // Recipients
      var recipients = $('#persistent-data-container').data('google-selected-sharing-addresses');
      recipients = recipients.concat( $('#persistent-data-container').data('windowslive-selected-sharing-addresses') );
      recipients = encodeURI( recipients.join(',') );
      // Subject
      var subject = encodeURI( getShareMessageWithCurrentParams() );
      // Body
      var letterResponseData = $('#persistent-data-container').data('server-response');
      var body = encodeURI( 'Come check it out at ' + letterResponseData.url_to_letter );
      $('#mailto-link').html('<a href="mailto:'+recipients+'?subject='+subject+'&body='+body+'">Email your contacts!</a>');
    });

    // TODO: URL link display
    //$('#url-to-clipboard').html('Clipboard-copiable link to go here:<br><a href="' + $('#persistent-data-container').data('server-response').url_to_letter + '>your letter</a>.');
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
  $('#end-step6-button').click(function() {
    window.location.href = $('#persistent-data-container').data('server-response').url_to_letter; // XXX: Replace this with the URL of the published letter
  });

})( jQuery );
