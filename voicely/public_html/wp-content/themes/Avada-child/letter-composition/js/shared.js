// Note: Must use "Jquery" rather than the shorthand "$" due to WordPress and scope limitations

// XXX - Edit this when changing servers
var detS = "";
if (location.protocol == 'https:') detS = "s";
var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/ajax/";
var imgLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/img/";

// Returns the default sharing message using the user's entered parameters from the first few steps
function getShareMessageWithCurrentParams(service) {

	// Addressees
  var addressees = jQuery('#toInput').tagsinput('items');

  // Remove "The World"
  addressees = addressees.filter(function( element ) {
    return element.twitter_handle !== 'The World';
  });

  // Array to string
  addressees = addressees.map( function(element){
    // Return twitter handles if using twitter, else just their recognizable name
    if ( service == 'twitter' )
      return element.twitter_handle;
    else
      return element.pretty_name;
  });
  if ( addressees.length > 1 ) {
    var last = addressees.pop();
    addressees = addressees.join(', ') + ' and ' + last;
  }

  // Default to "the world"
	// if ( addressees.length == 0 )
    // addressees = "the world";
  // Prefix with "to" if any elements exist
	if ( addressees.length != 0 )
    addressees = " to " + addressees;
  
	// Title
	var title = jQuery('#titleInput').val();
  
  // Combine & return
  var result = "";
  if ( title != "" )
    result = "I just wrote an open letter" + addressees + " entitled \"" + title + "\".";
  else
	  result = "I just wrote an open letter " + addressees + ".";
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
function getToken( provider, syslogin ) {
  var syslogin = syslogin || false; // Because IE doesn't support default parameters yet
	var url = ajaxLocation+"get-token.php?provider=" + encodeURI(provider) +
    '&' + new Date().getTime();
  if ( syslogin )
    url = url + '&syslogin';
	popupCenter( url, provider + " Authorization", 800, 800 );
}

// Called from popup which requests user's token (stores token in token variable)
function closePopupFromPopup( result, provider, syslogin ) {
  var syslogin = syslogin || 'false'; // Because IE doesn't support default parameters yet  
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

// Drag/drop
// Mark/initialize new elements as draggable
//   Called upon successful AJAX requests
function markAsDraggable() {
  jQuery('.draggable').each(function() {
    jQuery(this).draggable({
      start: function(event, ui) {
        jQuery(this).draggable("option", "cursorAt", {
          left: Math.floor(ui.helper.width() / 2),
          top: Math.floor(ui.helper.height() / 2)
        }); 
      },
      helper: 'clone', // don't move base element when dragged
      revert: 'invalid', // move back if dragged somewhere invalid
      appendTo: 'body', // escape parent
      scroll: false, // don't scroll the container when dragged to its bounds
      cursor: 'pointer',
      tolerance: 'pointer', // cursor is required overlap position
      delay: 180, // Must hold mouse down for long enough to initate drag sequence, to avoid detecting clicks as drags
    });
    jQuery(this).removeClass('draggable');
  });

  // Add url to body if it's from the addable images section
  jQuery("#bodyInput").droppable({
    drop: function( event, ui ) {
      // Don't allow anything if they aren't logged in
      if ( ! jQuery('#body-blocking-overlay').data('logged-in') === true )
        return;
      // Copy the content to the body
      var url = ui.draggable.attr('src');
      if (url && (ui.draggable.hasClass('addable-image')) )
        jQuery(this).val( jQuery(this).val() + "\n\n" + url + "\n\n" );
      else if (url && (ui.draggable.hasClass('recipient')) )
        jQuery(this).val( jQuery(this).val() + "\n\n" + url + "\n\n" );
    },
    accept: '.addable-image, .recipient'
  });
  
  // Add tag to selected tags list if it's dragged from the tags section
  jQuery("#form-tag").droppable({
    drop: function( event, ui ) {
      var tagId = ui.draggable.attr('id');
      // Select if unselected
      if ( jQuery('#'+tagId).data('isSelected') != true )
        ui.draggable.click(); // Fire its onclick
    },
    accept: '.tag'
  });
    
  // Add recipient to selected recipient list if it's dragged from the recipients section
  jQuery("#to-input-wrapper").droppable({
    drop: function( event, ui ) {
      var repipientId = ui.draggable.attr('id');
      // Select if unselected
      if ( jQuery('#'+repipientId).data('isSelected') != true )
        ui.draggable.click(); // Fire its onclick
    },
    accept: '.recipient'
  });
}

// Grey out elements which are loaded dynamically, when appropriate
function greyElementsIfAppropriate() {
  // If not logged in, grey the elements
  if ( ! jQuery('#body-blocking-overlay').data('logged-in') === true ) {
    jQuery('.tags .tag').each(function() {
      jQuery(this).css('background-color', '#888888');
      jQuery(this).css('opacity', '0.5');
    });
    jQuery('#to-input-wrapper .tag').each(function() {
      jQuery(this).css('background-color', '#888888');
      jQuery(this).css('opacity', '0.5');
    });
    jQuery('.scroll-recipient-label').each(function() {
      jQuery(this).css('background-color', '#888888');
      jQuery(this).css('opacity', '0.5');
    });
    jQuery('.addable-image').each(function() {
      jQuery(this).css('opacity', '0.5');
    });
  }
}