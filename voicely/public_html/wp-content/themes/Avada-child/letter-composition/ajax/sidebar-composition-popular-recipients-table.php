<?php
// This file renders recipients
//   If the offset would put us meyond the maximum number of recipients, instead returns "NONE".

// Configure PHP to throw exceptions for notices and warnings (to more easily debug via ajax)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
  throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
});

// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../../../../../wp-load.php');

$numPerRow = 3;
$numOfRows = 2;
$receivedIndex = isset($_GET['index']) ? $_GET['index'] : 0;

$recipients = get_terms(array(
  'taxonomy' => 'addressee',
  'hide_empty' => 0,
  'order' => 'DESC',
  'number' => $numPerRow * $numOfRows,
  'offset' => $receivedIndex * $numPerRow * $numOfRows,
));

// If we're out, return "NONE"
if ( sizeof($recipients) == 0 ) {
  echo "NONE";
  die();
}
?>
<span class="scroll-container-element">
  <span class="scroll-container-flex-element">
    <?php
      for ($i = 0; $i < $numOfRows; $i++) {
        echo "\t".'<div class="container-row">'."\n";
        for ($j = $i * $numPerRow; ($j < ($i+1) * $numPerRow); $j++) {
          echo "\t\t".'<span>'."\n";
          // If we have another recipient, display their data
          if ($j < sizeof($recipients)) {
            $current_term_id = $recipients[$j]->term_id;
            $current_term_name = $recipients[$j]->name;
            $current_prettyname = get_term_meta( $current_term_id, "pretty_name", true );
            // $testvar = var_export($recipients[$j], true);
            echo "\t\t\t".'<span>'."\n";
            echo "\t\t\t\t".'<img id="scroll-recipient-id-'.$current_term_id.'" src="https://voicely.org/wp-content/themes/Avada-child/letter-composition/img/64x64.png" onclick=\'addresseeClicked("'.$current_term_id.'", "'.$current_term_name.'", "'.$current_prettyname.'")\'>'."\n";
            echo "\t\t\t\t".'<span class="scroll-recipient-label">'.$current_prettyname.'</span>'."\n";
            echo "\t\t\t".'</span>'."\n";
            // echo "\t\t\t".'<span class="scroll-recipient-label">'.$testvar.'</span>'."\n";
          }
          echo "\t\t".'</span>'."\n";

        }
        echo "\t".'</div>'."\n";
      }
    ?>
  </span>
</span>