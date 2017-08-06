// This file contains the shared social sharing code for each of the social sharing JS components

// XXX - Edit this when changing servers
var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";

// Get user's third party authorization token
function getToken( provider ) {
  var url = ajaxLocation+"assets/get-token.php?provider=" + encodeURI(provider);      
  popupCenter( url, provider + " Authorization", 500, 500 );
}

// Opens a new popup, centered (for sharing prompts)
// From: http://www.xtf.dk/2011/08/center-new-popup-window-even-on.html
function popupCenter(url, title, w, h) {
  // Fixes dual-screen position                         Most browsers      Firefox
  var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
  var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

  var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
  var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

  var left = ((width / 2) - (w / 2)) + dualScreenLeft;
  var top = ((height / 2) - (h / 2)) + dualScreenTop;
  var newWindow = window.open(url, title, 'scrollbars=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

  // Puts focus on the newWindow
  if (window.focus) {
    newWindow.focus();
  }
}

// Note: Could be made to better handle arbitrary providers
function postToSocialMedia( provider, shareMessage, returnedRemoteLetterData, nonce ) {

  // Perform the AJAX request
  jQuery.get(ajaxLocation+"assets/post-to-social-media.php", 
  {
    provider: provider,
    message:  shareMessage,
    url:      returnedRemoteLetterData.url_to_letter,
    nonce:    nonce,
  }
  ).then(
    // Transmission success callback
    function( data ){
      // Access the server's response as JSON
      try {
        var returnedData = data;

        // Handle server-specified errors if present
        if ( returnedData.error === true ) {
          console.log(returnedData);
        }
        // Else no errors - proceed
        else {
          // Process response
          if ( provider.toLowerCase() === 'facebook' ) {
            facebookSuccess();
          } else if ( provider.toLowerCase() === 'twitter' ) {
            twitterSuccess();
          }
        }
      }
      // Handle server response access errors
      catch ( e ) {
          console.log(returnedData);
      }
    },
    // Transmission failure callback
    function( data ){
      console.log(returnedData);
    }
  );
}