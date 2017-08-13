// This file contains the shared social sharing code for each of the social sharing JS components
//    NOTE: Must use "jQuery" rather than the shorthand "$" syntax here.
//          (It's a WP thing)

// XXX - Edit this when changing servers
var detS = "";
if (location.protocol == 'https:') detS = "s";
var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/";

// Returns the default sharing message using the user's entered parameters from the first few steps
function getShareMessageWithCurrentParams() {
  // Addressees
  // Fetch the input & remove empty elements
  var addressees = jQuery('#persistent-data-container').data('addressees');
  if ( addressees.length == 0 )
    addressees = "the world";

  // Title
  var title = jQuery('#persistent-data-container').data('title').trim();

  // // Tags
  // var tags = $('#persistent-data-container').data('tags');

  // Combine & return
  var result = "I just wrote an open letter to " + addressees + " entitled \"" + title + "\".";
  return result;
}

// Get user's third party authorization token
function getToken( provider ) {
  var url = ajaxLocation+"assets/get-token.php?provider=" + encodeURI(provider) + '&' + new Date().getTime();
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
function postToSocialMedia( provider, shareMessage, returnedRemoteLetterData, nonce, successCallback, failureCallback ) {

  // Perform the AJAX request
  jQuery.get(ajaxLocation+"assets/post-to-social-media.php", 
  {
    provider: provider,
    message:  shareMessage,
    url:      returnedRemoteLetterData.url_to_letter,
    nonce:    nonce,
    cachebust: new Date().getTime(),
  }).then(
    // Transmission success callback
    function( data ){
      // Access the server's response as JSON
      try {
        // Handle server-specified errors if present
        if ( data && data.hasOwnProperty('error') ) {
          console.log('Server-specified error in getContacts');
          console.log(data);
          failureCallback(data);
        }
        // Else no errors - proceed
        else {
          // Process response
          successCallback(data);
        }
      }
      // Handle server response access errors
      catch ( e ) {
        console.log('Exception in getContacts');
        console.log(e);
        console.log(data);
        failureCallback(data);
      }
    },
    // Transmission failure callback
    function( data ){
      console.log('Transmission failure callback in postToSocialMedia');
      console.log(data);
      failureCallback(data);
    }
  );
}

function getContacts( provider, successCallback ) {
  // Perform the AJAX request
  jQuery.get(ajaxLocation+"assets/get-contacts.php", 
  {
    provider: provider,
    cachebust: new Date().getTime(),
  }
  ).then(
    // Transmission success callback
    function( data ){
      // Access the server's response as JSON
      try {
        // Handle server-specified errors if present
        if ( data && data.hasOwnProperty('error') ) {
          console.log('Server-specified error in getContacts');
          console.log(data);
        }
        // Else no errors - proceed
        else {
          // Process response
          successCallback(data[1]); // Skip 'success' element, send payload
        }
      }
      // Handle server response access errors
      catch ( e ) {
        console.log('Exception in getContacts');
        console.log(e);
        console.log(data);
      }
    },
    // Transmission failure callback
    function( data ){
      console.log('Transmission failure callback in getContacts');
      console.log(data);
    }
  );
}