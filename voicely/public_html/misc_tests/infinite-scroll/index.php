<!doctype html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <meta charset="utf-8">
  <title>Infinite Horizontal Scroll</title>
</head>

<body>
  <div class="rhs-composition-section">
  
    <!-- Arrow buttons -->
    <div class="mini-nav btn-group scroll-container-buttons">
    <!-- <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button> -->
    <!-- <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button> -->
    <button class="btn btn-default btn-xs left-button" type="button"><</button>
    <button class="btn btn-default btn-xs right-button" type="button">></button>
    </div>
    <div class="sidebar-heading">
      <h4>Heading/Label</h4>
    </div>

    <!-- Scrolling Container -->
    <div class="scroll-container-wrapper"> <!-- Sets width only -->
      <div class="scroll-container row">
        <?php include 'get-more.php' ?>
        <?php include 'get-more.php' ?>
      </div>
    </div>
    
  </div>
</body>

<script>
  var animationSpeed = 0;

  // Left scroll button clicked...
  $('.scroll-container-buttons .left-button').unbind('click').click( function () {
    // console.log("<");

    // Scroll one screen to the left
    var currentScrollPosition = $('.scroll-container').scrollLeft();
    var newScrollPosition = currentScrollPosition - $('.scroll-container').width();
    // $('.scroll-container').scrollLeft( newScrollPosition );
    $('.scroll-container').animate( { scrollLeft: newScrollPosition }, animationSpeed);
  });

  // Right scroll button clicked...
  $('.scroll-container-buttons .right-button').unbind('click').click( function () {

    // console.log(">");
    // console.log("scrollLeft Before: " + $('.scroll-container').scrollLeft());

    // Scroll one screen to the right
    var currentScrollPosition = $('.scroll-container').scrollLeft();
    var newScrollPosition = currentScrollPosition + $('.scroll-container').width();
    // $('.scroll-container').scrollLeft( newScrollPosition );
    $('.scroll-container').animate( { scrollLeft: newScrollPosition }, animationSpeed);

    // console.log("scrollLeft After: " + $('.scroll-container').scrollLeft());
    // console.log("width: " + $('.scroll-container').width());
    // console.log("scrollWidth: " + $('.scroll-container')[0].scrollWidth);
    // console.log("clientWidth: " + $('.scroll-container')[0].clientWidth);
    // console.log("offsetWidth: " + $('.scroll-container')[0].offsetWidth);
    
    // Scroll all the way to the right
    // $('.scroll-container').scrollLeft( $('.scroll-container')[0].scrollWidth );
  });

  // On scroll event...
  $('.scroll-container').scroll( function() {
    // console.log('Scroll position: ' + $('.scroll-container').scrollLeft());

    // If scroll is (nearly) maxxed out...
    var scrollbarRightPosition = $('.scroll-container').scrollLeft() + $('.scroll-container').width();
    var scrollbarMaxPosition = $('.scroll-container')[0].scrollWidth;
    if ( scrollbarRightPosition >= scrollbarMaxPosition-5 ) {

      // Request more content and add it to the element
      $.get('get-more.php', function(newContent) {
        $('.scroll-container').append(newContent);
      }); 
    }
   });

</script>

<style>
.mini-nav {
    float: right;
    margin-top: -2px;
    margin-left: -3px;
}
/* ADDED */
.scroll-container-wrapper {
  border-style: none;
  /* width: 500px; */
}
.scroll-container {
  border-style: none;
  width: 100%;
  overflow: hidden;
  /* overflow: auto; */
  white-space: nowrap;
}
.scroll-container .scroll-container-element {
  border-style: none;
  display: inline-block;
  width: 100%;
}
.scroll-container .scroll-container-element .row {
  display: flex;
}
.scroll-container .scroll-container-element > .row > span {
  flex-grow: 1;
  text-align: center;
}
.scroll-container .scroll-container-element > .row > span > img {
  width: 90%;
}
</style>

</html>