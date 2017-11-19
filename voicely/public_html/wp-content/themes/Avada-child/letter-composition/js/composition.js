// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  /* Scrolling sidebar panels implementation: */
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
      source: tagnames.ttAdapter(),
    },
    confirmKeys: [13, 44, 59, 32, 9], // Confirm on enter, comma, semicolon, space (ASCII codes)
    delimiter: [13, 44, 59, 32, 9], // Break on enter, comma, semicolon, space (ASCII codes)
    trimValue: true, // Trim whitespace from tags
    cancelConfirmKeysOnEmpty: true, // fix for carrying over comma to next tag
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
    confirmKeys: [13, 44, 59, 32, 9], // Confirm on enter, comma, semicolon, space (ASCII codes)
    delimiter: [13, 44, 59, 32, 9], // Break on enter, comma, semicolon, space (ASCII codes)
    trimValue: true, // Trim whitespace from addressees
    cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next addressee
  });
  // Add an event to update which elements are selected when new scroll container data is loaded
  $('#toInput').on( 'newScrollContainerContentLoaded', {}, function() {
    // For each selected recipient item...
    var selectedTags = $('#toInput').tagsinput('items');
    selectedTags.forEach(function(item, index){
      // Re-add it to trigger the selection functionality
      // Mark the element as selected
      jQuery('#scroll-recipient-id-'+item.term_id).data('isSelected', true);
      // Adjust display appropriately
      jQuery('#scroll-recipient-id-'+item.term_id).css('opacity', '0.25');  
    });
  });

  // Hooks to select/deselect recipients/tags in the scroll containers when added/removed
  // When an item is added to the recipients list, select it on the right side
  jQuery('#toInput').on('itemAdded', function(event) {
    // Mark the element as selected
    jQuery('#scroll-recipient-id-'+event.item.term_id).data('isSelected', true);
    // Adjust display appropriately
    jQuery('#scroll-recipient-id-'+event.item.term_id).css('opacity', '0.25');  
  });
  // When an item is removed from the recipients list, deselect it on the right side
  jQuery('#toInput').on('itemRemoved', function(event) {
    // Mark the element as selected
    jQuery('#scroll-recipient-id-'+event.item.term_id).data('isSelected', false);
    // Adjust display appropriately
    jQuery('#scroll-recipient-id-'+event.item.term_id).css('opacity', '1.0');  
  });

  // When an item is added to the tags list, select it on the right side
  jQuery('#tagsInput').on('itemAdded', function(event) {
    // Mark the element as selected
    jQuery('#related-tag-id-'+event.item).data('isSelected', true);
    // Adjust display appropriately
    jQuery('#related-tag-id-'+event.item).css('opacity', '0.25');  
  });
  // When an item is removed from the recipients list, deselect it on the right side
  jQuery('#tagsInput').on('itemRemoved', function(event) {
    // Mark the element as selected
    jQuery('#related-tag-id-'+event.item).data('isSelected', false);
    // Adjust display appropriately
    jQuery('#related-tag-id-'+event.item).css('opacity', '1.0');  
  });

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

  // Set login button's functionality
  jQuery('#fb-signin-button').click(function() {
    getToken( 'Facebook', true );
  });

  // Hide the body-blocking overlay & enable composition if the user is logged in
  if ( $('#body-blocking-overlay').data('logged-in') === true ) {
    $('#bodyInput').prop("disabled", false);
    $('#body-blocking-overlay').css('display', 'none');
  }

  $(document).ready(function() {
    // Fetch & set nonces
    nonceRequest( 'post' );
    nonceRequest( 'mark-shared' );
    nonceRequest( 'share-to-social-media' );
    nonceRequest( 'google-contacts' );

    // Saves letter progress.  Must be global so the sending script can stop progress saving.
    window.saveInterval = null;

    // Save the letter data to cookies every 10 seconds
    window.saveInterval = setInterval(function() {
      createCookie( 'savedTitle', $('#titleInput').val(), 3 );
      createCookie( 'savedTags', $('#tagsInput').tagsinput('items'), 3 );
      createCookie( 'savedAddressees', JSON.stringify( $('#toInput').tagsinput('items'), 3 ) );
      // Only save the body of the letter if the user is logged in
      //   (else it'll overwrite their saved letter, since it's not loaded in the first place)
      if ( $('#body-blocking-overlay').data('logged-in') === true )
        createCookie( 'savedLetter', $('#bodyInput').val(), 3 );
    }, 1000);

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
      // Only do so if the user is logged in
      if ( $('#body-blocking-overlay').data('logged-in') === true )
        $('#bodyInput').val( readCookie('savedLetter') );
    }

    // Workaround - clears the tagsinput element on "enter"
    //   (Solves persisting input if no autocomplete suggestion is selected when user presses "enter")
    $('.tags .twitter-typeahead').keypress(function(e){
      if (e.which == 13){ // Enter key pressed
        // Clear the input after the addition and readdition succeed
        setTimeout(function () {
          $('.tt-input').typeahead('val', '');
        }, 0);
      }
    });

  });

})( jQuery );

// Fires when an addressee is selected from the "Popular Recipients" section
//   Selects or deselects them appropriately
function addresseeClicked( addresseeId, addresseeName, addresseePrettyName ) {
  // Determine if the element has already been selected
  var previouslySelected = false;
  if ( jQuery('#scroll-recipient-id-'+addresseeId).data('isSelected') === true )
    previouslySelected = true;

  // Adds the clicked recipient to the selection list if not already selected
  if ( ! previouslySelected ) {
    var recipientToAdd = {
      twitter_handle: addresseeName,
      term_id: addresseeId,
      pretty_name: addresseePrettyName
    }
    jQuery('#toInput').tagsinput('add', recipientToAdd);
  }
  // Otherwise, removes the selected recipient
  else {
    var recipientToRemove = {
      twitter_handle: addresseeName,
      term_id: addresseeId,
      pretty_name: addresseePrettyName
    }
    jQuery('#toInput').tagsinput('remove', recipientToRemove);
  }
}

// Fires when a suggested tag is selected from the "Suggested Tags" section
//   Selects or deselects them appropriately
function suggestedTagClicked( tagId, tagName ) {
  // Determine if the element has already been selected
  var previouslySelected = false;
  if ( jQuery('#related-tag-id-'+tagName).data('isSelected') === true )
    previouslySelected = true;

  // Adds the clicked recipient to the selection list if not already selected
  if ( ! previouslySelected ) {
    jQuery('#tagsInput').tagsinput('add', tagName);
  }
  // Otherwise, removes the selected recipient
  else {
    jQuery('#tagsInput').tagsinput('remove', tagName);
  }
}