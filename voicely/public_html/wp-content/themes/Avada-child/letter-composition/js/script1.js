/* This file contains the JS display code for step 1 - letter composition */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";

  $.ajaxSetup({
    timeout: 15000,
    error: function(event, request, settings){
      alert("Ajax error");
      console.log(event);
      console.log(request);
      console.log(settings);
    }
  });

  nonceRequest( 'post' );
  nonceRequest( 'mark-shared' );
  nonceRequest( 'share-to-social-media' );

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
              detault:
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
        return $.map(list, function(tagname) {
          return { tag: tagname }; });
      }
    }
  });
  tagnames.initialize();

  // Modify "tags input" behavior
  $('#tagsInput').tagsinput({
    typeaheadjs: {
      tag: 'tagnames',
      displayKey: 'tag',
      valueKey: 'tag',
      source: tagnames.ttAdapter()
    },
    confirmKeys: [13/*, 44*/], // Confirm on enter only, not comma
    delimiter: 13, // Break on enter only, not comma
    trimValue: true, // Trim whitespace from tags
    cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next tag
  });


  // Initialize the addressee fetching object
  var addresseenames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('addressee'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: {
      url: ajaxLocation+'assets/addresseenames.php?cachebust=' + (new Date()).getTime(),
      filter: function(list) {
        return $.map(list, function(addresseename) {
          return { addressee: addresseename }; });
      }
    }
  });
  addresseenames.initialize();

  // Modify "addressees input" behavior
  $('#toInput').tagsinput({
    typeaheadjs: {
      addressee: 'addresseenames',
      displayKey: 'addressee',
      valueKey: 'addressee',
      source: addresseenames.ttAdapter()
    },
    confirmKeys: [13/*, 44*/], // Confirm on enter only, not comma
    delimiter: 13, // Break on enter only, not comma
    trimValue: true, // Trim whitespace from addressees
    cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next addressee
  });

  // Set "next" button up to store data from step one and set up step two
  $('#end-composition-button').click(function() {
    // Store data
    $('#persistent-data-container').data('addressees', $('#toInput').tagsinput('items'));
    $('#persistent-data-container').data('tags', $('#tagsInput').tagsinput('items'));
    $('#persistent-data-container').data('title', $('#titleInput').val());
    $('#persistent-data-container').data('body', $('#bodyInput').val());

    // Load the next script, depending on whether or not the user is logged in
    $.ajax({
      url: ajaxLocation+'assets/is-user-logged-in.php',
      type: 'POST',
      dataType: 'json',
      success: function(data){
        // If the user isn't logged in, ask them to log in or register
        if ( ! data ) {
          $('#html-display-container').load(ajaxLocation+'assets/step2a.php', function() {
              $.getScript(ajaxLocation+'js/script2a.js');
          });
        }
        // Else skip that step and proceed to 
        else {
          $('#html-display-container').load(ajaxLocation+'assets/step2b.php', function() {
              $.getScript(ajaxLocation+'js/script2b.js');
          });
        }
      }
    });

  });

})( jQuery );
