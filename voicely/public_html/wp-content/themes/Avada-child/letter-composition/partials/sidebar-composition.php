<!-- Section 1 - Popular Recipients -->
<div class="rhs-composition-section" id="composition-section-1">
  <!-- Arrow buttons -->
  <div class="mini-nav btn-group" id="popular-recipients-buttons">
    <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>
  <!-- Heading -->
  <div class="sidebar-heading">
    <h4>Popular Recipients</h4>
  </div>
  <!-- Popular Recipients Table -->
  <div id="popular-recipients-div">
    <?php include 'sidebar-composition-popular-recipients-table.php' ?>
  </div>
</div>

<!-- Section 2 - Tags -->
<div class="rhs-composition-section" id="composition-section-2">
  <!-- Arrow buttons -->
  <div class="mini-nav btn-group" id="tags-buttons">
    <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>
  <!-- Heading -->
  <div class="sidebar-heading">
    <h4>Tags</h4>
  </div>
  <!-- Tags Input -->
  <div class="tags">
    <!-- Tags Input -->
    <div class="form-group" id="form-tag">
      <textarea type="text" data-role="tagsinput" placeholder="Add a tag..." class="form-control" id="tagsInput" autocomplete="off"></textarea>
    </div>
    <!-- Suggested Tags -->
    <div class="form-group" id="form-tag-gray">
      <center>[SUGGESTED TAGS]</center>
    </div>
  </div>
</div>

<!-- Section 3 - Add images -->
<div class="rhs-composition-section">
  <!-- Arrow buttons -->
  <div class="mini-nav btn-group" id="add-image-buttons">
    <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>
  <div class="sidebar-heading">
    <h4>Add Images to Letter</h4>
  </div>
  <div id="add-images-div">
    <?php include 'sidebar-composition-add-images-table.php' ?>
  </div>
</div>