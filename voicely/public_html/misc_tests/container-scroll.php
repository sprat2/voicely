<!doctype html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <meta charset="utf-8">
  <title>Demo</title>
</head>

<body>
  <div id="div-display-window">
    <div id="all-inner-divs">
      <!-- Only fill this element with div elements of class "inner" -->
      <div class="inner" style="background-color: black;"></div>
      <div class="inner" style="background-color: lightblue;">
        <div style="background-color: blue; width: 100%; height: 50%;">
          Enough text to wrap.  Enough text to wrap.  Enough text to wrap.  Enough text to wrap.  Enough text to wrap.  Enough text to wrap.  Enough text to wrap.  Enough text to wrap.
        </div>
      </div>
      <div class="inner" style="background-color: green;"></div>
      <div class="inner" style="background-color: gray;"></div>
      <div class="inner" style="background-color: red;"></div>
      <div class="inner" style="background-color: yellow;"></div>
      <div class="inner" style="background-color: cyan;"></div>
    </div>
  </div>

  <button type="button" id="button-1">&#9664;</button>
  <button type="button" id="button-2">&#9654;</button>
  </body>

<style>
#div-display-window{
  height: 800px;
  width: 300px;
  overflow: hidden;
}
#all-inner-divs{
  height: 100%;
  /* width: (100% * numChildren); Set in script to autodetect child count */
  transition: all 0.3s;
}
.inner {
  display: block;
  float: left;
  height: 100%;
  /* width: (100% / numChildren); Set in script to autodetect child count */
}
</style>

<script>
/* Set up container sizes per number of children */
/* Limitation: Dynamically-added child elements won't have these rules applied.
 *             If we need these in the future, either reapply this process or change class rules. */
var numChildren = $('#all-inner-divs > div').length;
var numChildrenIncrement = 100/numChildren;
$('#all-inner-divs').css('width', 100*numChildren+'%');
$('.inner').css('width', numChildrenIncrement+'%');

/* Left and right buttons */
var transformIndex = 0;
$('#button-1').click(function() {
  ( transformIndex > 0 ) ? transformIndex-- : 0;
  $('#all-inner-divs').css('transform', 'translate(-'+numChildrenIncrement*transformIndex+'%)');
});
$('#button-2').click(function() {
  ( transformIndex < numChildren-1 ) ? transformIndex++ : numChildren-1;
  $('#all-inner-divs').css('transform', 'translate(-'+numChildrenIncrement*transformIndex+'%)');
});
</script>

</html>