// Empty JS for your own code to be here

// Initialize Bootstrap popover elements
$(function () {
  $('[data-toggle="popover"]').popover()
});


// Initialize the tag fetching object
var tagnames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: 'assets/tagnames.php?cachebust=' + (new Date()).getTime(),
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
  trimValue: true, // Trim whitespace from tags
  cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next tag
});


// Initialize the addressee fetching object
var addresseenames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('addressee'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: 'assets/addresseenames.php?cachebust=' + (new Date()).getTime(),
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
  trimValue: true, // Trim whitespace from addressees
  cancelConfirmKeysOnEmpty: false, // fix for carrying over comma to next addressee
});


// Set the "related tags" content appropriately
setRelatedTagsContent();

// Sets the "related tags" content appropriately
// TODO (eventually): Make this function act via NLP on body, tags, search params, etc.
function setRelatedTagsContent() {
  // Get related tags
  $.post( "assets/related-tags-html.php",
    {
      relatedDataElement: 'example one',
    },
    function( returnedData ) {
      // Set the popup element's content to this result
      var newRelatedTags = returnedData;
      $('#related-tags-button').attr('data-content', newRelatedTags);
  });
}