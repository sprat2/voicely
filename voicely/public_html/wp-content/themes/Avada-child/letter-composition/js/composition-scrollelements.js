// Encapsulates code, applying $ to JQuery the WP way
(function( $ ) {
  'use strict';

  // Infinite scroll containers
  var scrollAnimationSpeed = 200;
  var emulatedAjaxDelay = 0;
  
  /*******************************************************************
   *********************  SCROLL CONTAINER 1  ************************
   *******************************************************************/
  var displayedSetIndex1 = 0;
  var loadedSetIndex1 = 0;
  var listComplete1 = false;
  // Left scroll button clicked...
  $('#composition-section-1 .scroll-container-buttons .left-button').unbind('click').click( function () {
    // Scroll one screen to the left
    // Update scroll index
    displayedSetIndex1 = ( displayedSetIndex1 > 0 ) ? displayedSetIndex1-1 : 0;
    // Update scroll position
    $('#composition-section-1 .scroll-container').scroll();
  });

  // Right scroll button clicked...
  $('#composition-section-1 .scroll-container-buttons .right-button').unbind('click').click( function () {
    // Scroll one screen to the right
    // Update scroll index
    displayedSetIndex1++;
    // Update scroll position
    $('#composition-section-1 .scroll-container').scroll();
  });

  // On scroll event...
  $('#composition-section-1 .scroll-container').scroll( function() {
    var scrollbarMaxPosition1 = $('#composition-section-1 .scroll-container')[0].scrollWidth;
    // Update scroll position
    var currentScrollPosition1 = $('#composition-section-1 .scroll-container').scrollLeft();    
    var newScrollPosition1 = $('#composition-section-1 .scroll-container')[0].getBoundingClientRect().width * displayedSetIndex1;
    // If we've more than maxxed out, take a step back and only scroll to the max length
    if ( newScrollPosition1 > scrollbarMaxPosition1 ) {
      newScrollPosition1 = scrollbarMaxPosition1;
      displayedSetIndex1--;
    }
    if ( currentScrollPosition1 != newScrollPosition1 ) {
      // TODO: Make this more efficient (Only cancel animation if isn't going to right place)
      $('#composition-section-1 .scroll-container').stop(); // Stop previous animations to update to the new position
      $('#composition-section-1 .scroll-container').animate( { scrollLeft: newScrollPosition1 }, scrollAnimationSpeed);
    }
    // If scroll is (nearly) maxxed out...
    var scrollbarRightPosition1 = $('#composition-section-1 .scroll-container').scrollLeft() + $('#composition-section-1 .scroll-container').width();
    if ( scrollbarRightPosition1 >= scrollbarMaxPosition1 ) {
      // If we haven't finished this list, request more content and add it to the element
      if ( ! listComplete1 )
        $.get(ajaxLocation+'/sidebar-composition-popular-recipients-table.php?index='+loadedSetIndex1++, function(newContent) {
          setTimeout(function() {
            if (newContent === "NONE")
              listComplete1 = true;
            else {
              $('#composition-section-1 .scroll-container').append(newContent);
              $('#composition-section-1 .scroll-container').scroll(); // Update scroll position
            }
          }, emulatedAjaxDelay);    
        }); 
    }
  });
  // Trigger first scroll event to load default content
  $('#composition-section-1 .scroll-container').scroll();
  

  /*******************************************************************
   *********************  SCROLL CONTAINER 2  ************************
   *******************************************************************/
  var displayedSetIndex2 = 0;
  var loadedSetIndex2 = 0;
  var listComplete2 = false;
  // Left scroll button clicked...
  $('#composition-section-2 .scroll-container-buttons .left-button').unbind('click').click( function () {
    // Scroll one screen to the left
    // Update scroll index
    displayedSetIndex2 = ( displayedSetIndex2 > 0 ) ? displayedSetIndex2-1 : 0;
    // Update scroll position
    $('#composition-section-2 .scroll-container').scroll();
  });

  // Right scroll button clicked...
  $('#composition-section-2 .scroll-container-buttons .right-button').unbind('click').click( function () {
    // Scroll one screen to the right
    // Update scroll index
    displayedSetIndex2++;
    // Update scroll position
    $('#composition-section-2 .scroll-container').scroll();
  });

  // On scroll event...
  $('#composition-section-2 .scroll-container').scroll( function() {
    var scrollbarMaxPosition2 = $('#composition-section-2 .scroll-container')[0].scrollWidth;
    // Update scroll position
    var currentScrollPosition2 = $('#composition-section-2 .scroll-container').scrollLeft();    
    var newScrollPosition2 = $('#composition-section-2 .scroll-container')[0].getBoundingClientRect().width * displayedSetIndex2;
    // If we've more than maxxed out, take a step back and only scroll to the max length
    if ( newScrollPosition2 > scrollbarMaxPosition2 ) {
      newScrollPosition2 = scrollbarMaxPosition2;
      displayedSetIndex2--;
    }
    if ( currentScrollPosition2 != newScrollPosition2 ) {
      // TODO: Make this more efficient (Only cancel animation if isn't going to right place)
      $('#composition-section-2 .scroll-container').stop(); // Stop previous animations to update to the new position
      $('#composition-section-2 .scroll-container').animate( { scrollLeft: newScrollPosition2 }, scrollAnimationSpeed);
    }
    // If scroll is (nearly) maxxed out...
    var scrollbarRightPosition2 = $('#composition-section-2 .scroll-container').scrollLeft() + $('#composition-section-2 .scroll-container').width();
    if ( scrollbarRightPosition2 >= scrollbarMaxPosition2 ) {
      // If we haven't finished this list, request more content and add it to the element
      if ( ! listComplete2 )
        $.get(ajaxLocation+'/sidebar-composition-add-images-table.php?index='+loadedSetIndex2++, function(newContent) {
          setTimeout(function() {
            if (newContent === "NONE")
              listComplete2 = true;
            else {
              $('#composition-section-2 .scroll-container').append(newContent);
              $('#composition-section-2 .scroll-container').scroll(); // Update scroll position
            }
          }, emulatedAjaxDelay);    
        }); 
    }
  });
  // Trigger first scroll event to load default content
  $('#composition-section-2 .scroll-container').scroll();

})( jQuery );