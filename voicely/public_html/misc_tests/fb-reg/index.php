<?php

$debug = true;
// Display errors if debugging
if ( $debug ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../wp-load.php')

?>
<!doctype html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <meta charset="utf-8">
  <title>Demo</title>
</head>
<body>

<p>(Only show this if no one's signed in when user accesses letter composition page)</p>
<button type="button" id="fb-signin-button">Sign in with Facebook</button>

<style>
</style>

<script>
// XXX - Edit this when changing servers
var detS = "";
if (location.protocol == 'https:') detS = "s";
var ajaxLocation = "http" + detS + "://voicely.org/misc_tests/fb-reg/";

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

// var token = "";
// Get user's third party authorization token
function getToken( provider ) {
  var url = ajaxLocation+"get-token.php?provider=" + encodeURI(provider) + '&' + new Date().getTime();
  popupCenter( url, provider + " Authorization", 500, 500 );
}

// Called from popup which requests user's token (stores token in token variable)
function closePopupFromPopup( result ) {
  // token = result;
  // console.log(token);
  useToken( result );
}

function useToken( theToken ) {
  // Perform the AJAX request
  jQuery.get(ajaxLocation+"reg-or-login.php", 
  {
    provider: 'Facebook',
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


// Set button's functionality
$('#fb-signin-button').click(function() {
  getToken('Facebook');
})
</script>

</body>
</html>