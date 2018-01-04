// Note: Must use "Jquery" rather than the shorthand "$" due to WordPress and scope limitations

// XXX - Edit this when changing servers
var detS = "";
if (location.protocol == 'https:') detS = "s";
var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/ajax/";

// Returns the default sharing message using the user's entered parameters from the first few steps
function getShareMessageWithCurrentParams() {

	// Addressees
  var addressees = jQuery('#toInput').tagsinput('items');
  addressees = addressees.map( function(element){ return element.twitter_handle; });
	if ( addressees.length == 0 )
	  addressees = "the world";
  
	// Title
	var title = jQuery('#titleInput').val();
  
  // Combine & return
  var result = "";
  if ( title != "" )
    result = "I just wrote an open letter to " + addressees + " entitled \"" + title + "\".";
  else
	  result = "I just wrote an open letter to " + addressees + ".";
	return result;
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

// Get user's third party authorization token
function getToken( provider, syslogin = false ) {
	var url = ajaxLocation+"get-token.php?provider=" + encodeURI(provider) +
    '&' + new Date().getTime();
  if ( syslogin )
    url = url + '&syslogin';
	popupCenter( url, provider + " Authorization", 800, 800 );
}

// Called from popup which requests user's token (stores token in token variable)
function closePopupFromPopup( result, provider, syslogin = 'false' ) {
  if (syslogin == 'true')
    useTokenToLogin( result, provider );
  else
    useToken( result, provider );
}

function useTokenToLogin( theToken, provider ) {
	// Perform the AJAX request
	jQuery.get(ajaxLocation+"reg-or-login.php", 
	{
		provider: provider,
		token: theToken,
		cachebust:  new Date().getTime(),
	}).then(
		// Transmission success callback
		function( data ){
			// Access the server's response as JSON
			try {
				// Handle server-specified errors if present
				if ( data && data.hasOwnProperty('error') ) {
					console.log('Server-specified error in useToken()');
				}
				// Else no errors - proceed
				else {
					// Process response
					console.log( 'Success.  Result:' );
					console.log( data );
					// Reload the page now that the user is logged in to an account
					window.location.reload(true);
				}
			}
			// Handle server response access errors
			catch ( e ) {
				console.log('Exception in useToken()');
				console.log( [data,e] );
			}
		},
		// Transmission failure callback
		function( data ){
			console.log('Transmission failure callback in useToken()');
			console.log( data );
		}
	);
}

function useToken( theToken, provider ) {
	// Store the token in the appropriate DOM object
  jQuery('#tokenholder').data( provider.toLowerCase()+'-token', theToken );

	// Special extra handling for services if their handling functions are defined
  switch ( provider.toLowerCase() ) {
		case 'facebook':
			if (typeof facebookTokenCallback === 'function')
      	facebookTokenCallback( theToken ); // in facebook.js
      break;
    case 'twitter':
      if (typeof twitterTokenCallback === 'function')
        twitterTokenCallback( theToken ); // in twitter.js
      break;
    case 'google':
      if (typeof loadGoogleContacts === 'function')
        loadGoogleContacts( theToken ); // in google.js
      break;
    case 'windowslive':
			if (typeof loadWindowsliveContacts === 'function')
				loadWindowsliveContacts( theToken ); // in windowslive.js
      break;
  }
}

function getContacts( provider, token, successCallback ) {
  // Perform the AJAX request
  jQuery.get(ajaxLocation+"get-contacts.php", 
  {
    provider: provider,
    token: token,
    cachebust: new Date().getTime(),
  }
  ).then(
    // Transmission success callback
    function( data ){
      // Access the server's response as JSON
      try {
        // Handle server-specified errors if present
        if ( data && data.hasOwnProperty('error') ) {
          console.log('Server-specified error in getContacts()');
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
        console.log('Exception in getContacts()');
        console.log(e);
        console.log(data);
      }
    },
    // Transmission failure callback
    function( data ){
      console.log('Transmission failure callback in getContacts()');
      console.log(data);
    }
  );
}

// Note: Could be made to better handle arbitrary providers
function postToSocialMedia( provider, token, shareMessage, returnedRemoteLetterData, nonce, successCallback, failureCallback ) {
  // Perform the AJAX request
  jQuery.get(ajaxLocation+"post-to-social-media.php", 
  {
    provider:   provider,
    token:      token,
    message:    shareMessage,
    url:        returnedRemoteLetterData.url_to_letter,
    nonce:      nonce,
    cachebust:  new Date().getTime(),
  }).then(
    // Transmission success callback
    function( data ){
      // Access the server's response as JSON
      try {
        // Handle server-specified errors if present
        if ( data && data.hasOwnProperty('error') ) {
          console.log('Server-specified error in postToSocialMedia()');
          failureCallback( data );
        }
        // Else no errors - proceed
        else {
          // Process response
          successCallback(data);
        }
      }
      // Handle server response access errors
      catch ( e ) {
        console.log('Exception in postToSocialMedia()');
        failureCallback( [data,e] );
      }
    },
    // Transmission failure callback
    function( data ){
      console.log('Transmission failure callback in postToSocialMedia()');
      failureCallback(data);
    }
  );
}