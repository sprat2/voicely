var saveInterval = null;

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  /* Scrolling sidebar implementation: */
  /* Set up container sizes per number of children */
  /* Limitation: Dynamically-added child elements won't have these rules applied.
  *             If we need these in the future, either reapply this process or change class rules. */
  var numChildren = $('#all-inner-divs > div').length;
  var numChildrenIncrement = 100/numChildren;
  $('#all-inner-divs').css('width', 100*numChildren+'%');
  $('.inner').css('width', numChildrenIncrement+'%');
  /* Left and right buttons */
  var transformIndex = 0;
  $('#sidebar-left').click(function() {
    ( transformIndex > 0 ) ? transformIndex-- : 0;
    $('#all-inner-divs').css('transform', 'translate(-'+numChildrenIncrement*transformIndex+'%)');
  });
  $('#sidebar-right').click(function() {
    ( transformIndex < numChildren-1 ) ? transformIndex++ : numChildren-1;
    $('#all-inner-divs').css('transform', 'translate(-'+numChildrenIncrement*transformIndex+'%)');
  });

  
  // Fetch nonces
  nonceRequest( 'post' );
  nonceRequest( 'mark-shared' );
  nonceRequest( 'share-to-social-media' );
  nonceRequest( 'google-contacts' );

  // Handles a single request to set a nonce.
  function nonceRequest( action ) {
    $.get( ajaxLocation+"/get-wp-nonce.php", 
    {
      nonce_action: action,
    }
    ).then(
      // Transmission success callback
      function( data ){
        try {
          // Handle server-specified errors if present
          if ( data.error === true ) {
              console.log(data);
          }
          
          // Else no errors - proceed
          else {
            // Process response
            switch ( data.action ) {
              case 'post':
                $('#nonceholder').data( 'post-nonce', data.nonce );
                break;
              case 'mark-shared':
                $('#nonceholder').data( 'share-mark-nonce', data.nonce );
                break;
              case 'share-to-social-media':
                $('#nonceholder').data( 'shared-to-social-media-nonce', data.nonce );
                break;
              case 'google-contacts':
                $('#nonceholder').data( 'google-contacts-nonce', data.nonce );
                break;
              default:
                console.log(data);
            }
          }
        }
        // Handle server response access errors
        catch ( e ) {
            console.log(data);
        }
      },
      // Transmission failure callback
      function( data ){
          console.log(data);
      }
    );
  }


  // Initialize Bootstrap popover elements
  $(function () {
    $('[data-toggle="popover"]').popover()
  });

  // Initialize the tag fetching object
  var tagnames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
      url: ajaxLocation+'tag-names.php?cachebust=' + (new Date()).getTime(),
      filter: function(list) {
        return $.map(list, function(data) {
          return {
            tag: data,
          };
        });
      }
    }
  });
  tagnames.initialize();
  // Modify "tags input" behavior
  $('#tagsInput').tagsinput({
    typeaheadjs: {
      minLength: 3, // Doesn't work - hardcoded in typeahead source instead
      displayKey: 'tag',
      valueKey: 'tag',
      source: tagnames.ttAdapter()
    },
    confirmKeys: [13, 44, 59, 32], // Confirm on enter, comma, semicolon, space (ASCII codes)
    delimiter: [13, 44, 59, 32], // Break on enter, comma, semicolon, space (ASCII codes)
    trimValue: true, // Trim whitespace from tags
    cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next tag
  });


  // Initialize the addressee fetching object
  var addresseenames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('pretty_name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
      url: ajaxLocation+'addressee-names.php?cachebust=' + (new Date()).getTime(),
      filter: function(list) {
        return $.map(list, function(data) {
          return {
            value: data,
            addressee: data.twitter_handle,
            term_id: data.term_id,
            pretty_name: data.pretty_name,
            pretty_name_without_commas: data.pretty_name.split(",").join(""),
          };
        });
      }
    }
  });
  addresseenames.initialize();
  // Modify "addressees input" behavior
  $('#toInput').tagsinput({
    typeaheadjs: {
      minLength: 3, // Doesn't work - hardcoded in typeahead source instead
      displayKey: 'pretty_name_without_commas',
      valueKey: 'value', // Value autocompleted
      source: addresseenames.ttAdapter()
    },
    itemValue: 'pretty_name', // Shows when locked in
    // Note: These settings are currently useless, as new objects can't be added via text-input
    confirmKeys: [13, 44, 59, 32], // Confirm on enter, comma, semicolon, space (ASCII codes)
    delimiter: [13, 44, 59, 32], // Break on enter, comma, semicolon, space (ASCII codes)
    trimValue: true, // Trim whitespace from addressees
    cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next addressee
  });


  // Restore in-progress letter parameters, if detected
  // If there's a cookie with a title value, load it back in
  if ( readCookie('savedTitle') != null ) {
    $('#titleInput').val( readCookie('savedTitle') );
  }
  // If there's a cookie with a tags value, load it back in
  if ( readCookie('savedTags') != null ) {
    var savedTags = readCookie('savedTags').split(',');
    savedTags.forEach(function(tag){
      $('#tagsInput').tagsinput('add', tag);
    });
  }
  // If there's a cookie with an addressees value, load it back in
  if ( readCookie('savedAddressees') != null ) {
    try {
      var savedAddressees = JSON.parse( readCookie('savedAddressees') );
      savedAddressees.forEach(function(addressee){
        $('#toInput').tagsinput('add', addressee);
      });
    }
    catch(e) {
      console.log(e);
    }
  }
  // If there's a cookie with a body value, load it back in
  if ( readCookie('savedLetter') != null ) {
    $('#bodyInput').val( readCookie('savedLetter') );
  }

  // Save the letter data to cookies every 10 seconds
  saveInterval = setInterval(function() {
    createCookie( 'savedTitle', $('#titleInput').val(), 3 );
    createCookie( 'savedTags', $('#tagsInput').tagsinput('items'), 3 );
    createCookie( 'savedAddressees', JSON.stringify( $('#toInput').tagsinput('items'), 3 ) );
    createCookie( 'savedLetter', $('#bodyInput').val(), 3 );
  }, 1000);

  // Cookie handling functions
  function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
  }
  function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  }

})( jQuery );