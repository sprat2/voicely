<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<button id='auth-button'>Auth</button>
<button id='post-button'>Post</button>
<br>
<button id='refresh-button'>Fresh Refresh</button>

<script>

var ajaxLocation = 'http://voicely.org/ha-test/twit-test/';

// Clear cookies for a fresh run
<?php
if ( !isset( $_GET['cookiesCleared'] ) ) {
  echo '$.get( ajaxLocation+"delete-auth-cookies.php" );';
  echo 'window.location.href = ajaxLocation+"test.php?cookiesCleared";';
}
?>

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

var token = "";
function closePopupFromPopup( result ) {
  // alert("result of popup is: " + result);
  console.log('Resulting token from popup:');
  console.log(result);
  token = result;
}

// Auth button
$('#auth-button').click(function(){
  getToken('Twitter');
});

// Get user's third party authorization token
function getToken( provider ) {
  var url = ajaxLocation+"get-token.php?provider=" + encodeURI(provider) + '&' + new Date().getTime();
  popupCenter( url, provider + " Authorization", 500, 500 );
}

// Post button
$('#post-button').click(function(){
  nonceRequest('share-to-social-media');
});

// Handles a single request to set a nonce.
function nonceRequest( action ) {
  $.get( ajaxLocation+"get-wp-nonce.php", 
  {
    nonce_action: action,
  }
  ).then(
    // Transmission success callback
    function( data ){
      try {
        // Handle server-specified errors if present
        if ( data.error === true ) {
          console.log('error 1 in nonceRequest');
          console.log(data);
        }
        // Else no errors - proceed
        else {
          var fakeLetter = { 'url_to_letter' : 'falseURL' };
          postToSocialMedia( 'Twitter', token, 'HelloWorld!', fakeLetter, data.nonce,
            function(result){console.log('SuccessCallback');console.log(result);},
            function(result){console.log('FailureCallback');console.log(result);}
          );
        }
      }
      // Handle server response access errors
      catch ( e ) {
        console.log('error 2 in nonceRequest');
        console.log(data);
      }
    },
    // Transmission failure callback
    function( data ){
      console.log('error 3 in nonceRequest');
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

// Fresh refresh button
$('#refresh-button').click(function(){
  <?php echo 'window.location.href = ajaxLocation+"test.php";'; ?>
});
</script>