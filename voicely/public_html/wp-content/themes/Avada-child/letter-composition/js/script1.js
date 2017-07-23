/* This file contains the JS display code for step 1 - letter composition */

// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // XXX - Edit this when changing servers
  var ajaxLocation = "http://voicely.org/wp-content/themes/Avada-child/letter-composition/";

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
    $('#persistent-data-container').data('addressees', $('#toInput').tagsinput('items'));
    $('#persistent-data-container').data('tags', $('#tagsInput').tagsinput('items'));
    $('#persistent-data-container').data('title', $('#titleInput').val());
    $('#persistent-data-container').data('body', $('#bodyInput').val());

    // Load the next script
    $('#html-display-container').load(ajaxLocation+'assets/step2.php', function() {
        $.getScript(ajaxLocation+'js/script2.js');
    });
  });

})( jQuery );
