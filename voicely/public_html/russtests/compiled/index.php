<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Open Letter</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="css/typeaheadjs.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="container-fluid Col2">

      <!-- Header -->
      <div class="row">
        <div class="col-md-12">
          <div class="page-header">
            <img src="Open_Letter_Logo.jpg" ALT="Logo" width="144" height="42">
            <h4>Your Open Letter</h4>
          </div>
        </div>
      </div>
      
      <!-- Left and right sides -->
      <div class="row">

        <!-- Left side -->
        <div class="col-md-9" id="text-inputs">
          <form role="form">

            <!-- To: -->
            <div class="form-group">
              <input type="text" class="form-control" placeholder="To" id="toInput" data-role="tagsinput">
            </div>

            <!-- Title: -->
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Title" id="titleInput">
            </div>

            <!-- Body: -->
            <div class="form-group">
              <textarea type="message" style="height:325px;" class="form-control" placeholder="Write something important" id="bodyInput"></textarea>
            </div>

          </form>
        </div>

        <!-- Right side -->
        <div class="right-side-bar">
          <div class="col-md-3">
            <form role="form">
              <div class="form-group">

                <!-- Tags input -->
                <h4>Tags</h4>
                <textarea type="text" data-role="tagsinput" style="height:136px" placeholder="Add a tag..." class="form-control" id="tagsInput"></textarea>

                <!-- Related Tags button -->
                <button id="related-tags-button" type="button" class="btn btn-default" data-toggle="popover" data-placement="bottom" data-container="body" data-html="true" data-content="Fetching...">Related Tags</button>
                
                <!-- Related recipients display -->
                <h3>More recipients</h3>
                <div class="more-recipients">
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                  <a href="#"><img src="block_small.jpg" alt="recipients" width="44" height="44"></a>
                </div>

              </div>
            </div>
          </form>
        </div>

      </div>

      <!-- "Next" button -->
      <div class=form-progress>
        <button type="Next" class="btn btn-large btn-primary" style="width: 135px;">NEXT</button>
      </div>

    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-tagsinput.js"></script>
    <script src="js/typeahead.bundle.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>
