<!-- Section 1 - Popular Recipients -->
<?php // (This section labelled as section 1 in composition-scrollelements.js) ?>
<div class="rhs-composition-section" id="composition-section-1">
  <!-- Arrow buttons -->
  <div class="mini-nav btn-group scroll-container-buttons" id="popular-recipients-buttons">
    <button class="btn btn-default btn-xs far-left-button" type="button"><span class="glyphicon glyphicon-fast-backward"></span></button>
    <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>
  <!-- Heading -->
  <div class="sidebar-heading">
    <h4>Popular Recipients</h4>
  </div>
  <!-- Popular Recipients Scrolling Container -->
  <div class="scroll-container-wrapper"> <!-- Sets width only -->
    <div class="scroll-container">
      <?php //include dirname(__FILE__).'/../sidebar-composition-popular-recipients-table.php' ?>
    </div>
  </div>
</div>

<!-- Section 3 - Tags -->
<div class="rhs-composition-section" id="composition-section-3">
  <!-- Arrow buttons -->
  <!-- <div class="mini-nav btn-group" id="tags-buttons">
    <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
  </div> -->
  <!-- Heading -->
  <div class="sidebar-heading">
    <h4>Tags</h4>
  </div>
  <!-- Tags Input -->
  <div class="tags">
    <!-- Tags Input -->
    <div class="form-group" id="form-tag">
      <textarea type="text" data-role="tagsinput" placeholder="Add a tag..." class="form-control" id="tagsInput" autocomplete="off" disabled></textarea>
    </div>
    <!-- Suggested Tags -->
    <div class="form-group" id="form-tag-gray">
      <?php include dirname(dirname(__FILE__)).'/ajax/suggested-tags.php' ?>
    </div>
  </div>
</div>

<!-- Section 2 - Add images -->
<?php // (This section labelled as section 2 in composition-scrollelements.js) ?>
<div class="rhs-composition-section" id="composition-section-2">
  <!-- Arrow buttons -->
  <div class="mini-nav btn-group scroll-container-buttons" id="add-image-buttons">
    <button class="btn btn-default btn-xs far-left-button" type="button"><span class="glyphicon glyphicon-fast-backward"></span></button>
    <button class="btn btn-default btn-xs left-button" type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
    <button class="btn btn-default btn-xs right-button" type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
  </div>
  <!-- Heading -->
  <div class="sidebar-heading">
    <h4>Add Images to Letter</h4>
  </div>
  <!-- Add Images Scrolling Container -->
  <div class="scroll-container-wrapper"> <!-- Sets width only -->
    <div class="scroll-container">
      <?php //include dirname(__FILE__).'/../sidebar-composition-add-images-table.php' ?>
    </div>
  </div>
</div>