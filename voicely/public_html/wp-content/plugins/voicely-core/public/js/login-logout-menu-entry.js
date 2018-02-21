(function( $ ) {
	'use strict';

	$(window).load( function() {
		$('.login-logout-in').click(function() {
			getToken( 'Facebook', true );
		});
	});
	
})( jQuery );

// Note: Must use "Jquery" rather than the shorthand "$" due to WordPress and scope limitations

// XXX - Edit this when changing servers (NOTE: Reliance on the THEME from within this PLUGIN)
var detS = "";
if (location.protocol == 'https:') detS = "s";
var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/ajax/";

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