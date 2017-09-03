/* This file contains the JS display code for step 1 - letter composition */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var detS = "";
  if (location.protocol == 'https:') detS = "s";
  var ajaxLocation = "http" + detS + "://voicely.org/wp-content/themes/Avada-child/letter-composition/";
  
  // Custom AJAX eror handler - we're going to need it, since we load our scripts dynamically
  //    (silently errors otherwise)
  $.ajaxSetup({
    timeout: 15000,
    error: function(event, request, settings){
      console.log("Ajax error (next 3 lines)");
      console.log(event);
      console.log(request);
      console.log(settings);
    }
  });

  // Delete past social media authorization cookies, so user may elect or abstain this session as well
  $.get( ajaxLocation+"assets/delete-auth-cookies.php" );

  // Fetch nonces
  nonceRequest( 'post' );
  nonceRequest( 'mark-shared' );
  nonceRequest( 'share-to-social-media' );
  nonceRequest( 'gmail-contacts' );

  // Handles a single request to set a nonce.
  function nonceRequest( action ) {
    $.get( ajaxLocation+"/assets/get-wp-nonce.php", 
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
                $('#persistent-data-container').data('post-nonce', data.nonce);
                break;
              case 'mark-shared':
                $('#persistent-data-container').data('share-mark-nonce', data.nonce);
                break;
              case 'share-to-social-media':
                $('#persistent-data-container').data('shared-to-social-media-nonce', data.nonce);
                break;
              case 'gmail-contacts':
                $('#persistent-data-container').data('gmail-contacts-nonce', data.nonce);
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

  // Set the "related tags" content appropriately
  setRelatedTagsContent();

  // Sets the "related tags" content appropriately
  // TODO (eventually): Make this function act via NLP on body, tags, search params, etc.
  function setRelatedTagsContent() {
    // Get related tags
    $.post( ajaxLocation+"assets/related-tags-html.php",
      {
        relatedDataElement: 'example one',
      },
      function( returnedData ) {
        // Set the popup element's content to this result
        var newRelatedTags = returnedData;
        $('#related-tags-button').attr('data-content', newRelatedTags);
    });
  }

  // Initialize the tag fetching object
  var tagnames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
      url: ajaxLocation+'assets/tagnames.php?cachebust=' + (new Date()).getTime(),
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
      url: ajaxLocation+'assets/addresseenames.php?cachebust=' + (new Date()).getTime(),
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
  var saveInterval = setInterval(function() {
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

  // Set "next" button up to store data from step one and set up step two
  $('#end-composition-button').click(function() {
    // Store data
    $('#persistent-data-container').data('tags', $('#tagsInput').tagsinput('items'));
    $('#persistent-data-container').data('title', $('#titleInput').val());
    $('#persistent-data-container').data('body', $('#bodyInput').val());
    // Convert selected addressees object to twitter handles for internal handling
    var selectedAddressees = $('#toInput').tagsinput('items');
    selectedAddressees = selectedAddressees.map( function(element){
      return element.twitter_handle;
    });
    $('#persistent-data-container').data('addressees', selectedAddressees);

    // Stop the letter saving mechanism
    clearInterval( saveInterval );

    // Load the next script, depending on whether or not the user is logged in
    // $.ajax({
    //   url: ajaxLocation+'assets/is-user-logged-in.php',
    //   type: 'POST',
    //   dataType: 'json',
    //   success: function(data){
    //     // If the user isn't logged in, ask them to log in or register
    //     if ( ! data ) {
    //       $('#html-display-container').load(ajaxLocation+'assets/step2a.php', function() {
    //           $.getScript(ajaxLocation+'js/script2a.js');
    //       });
    //     }
    //     // Else skip that step and proceed to 
    //     else {
    //       $('#html-display-container').load(ajaxLocation+'assets/step2b.php', function() {
    //           $.getScript(ajaxLocation+'js/script2b.js');
    //       });
    //     }
    //   }
    // });

    // Skip the two deprecated frames and proceed
    $('#html-display-container').load(ajaxLocation+'assets/step3a1.php', function() {
      $.getScript(ajaxLocation+'js/script3a1.js');
    });

  });
  
})( jQuery );
