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
  'orderby' => 'count',
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

            // echo '<pre>';
            // var_export( $current_term_name );
            // die();

            // XXX: TODO: This is a workaround due to an SSL error.
            //      THIS SHOULD NOT PERSIST ANY LONGER THAN NECESSARY.
            stream_context_set_default( [
              'ssl' => [
                  'verify_peer' => false,
                  'verify_peer_name' => false,
              ],
            ]);

            // Get image url and determine if image exists at that url
            $current_img_url = get_term_meta( $current_term_id, "img_url", true );
            if ( !empty($current_img_url) ) {
              $image_headers = @get_headers($current_img_url);
              if( !$image_headers || strpos($image_headers[0], '404') )
                $image_exists = false;
              else
                $image_exists = true;
            }
            else
              $image_exists = false;
          
            // Echo the output for this recipient
            // $testvar = var_export($recipients[$j], true);
            echo "\t\t\t".'<span>'."\n";
            // Include their proper image, or a placeholder image if image if theirs doesn't exist
            if ( $image_exists )
              echo "\t\t\t\t".'<img id="scroll-recipient-id-'.$current_term_id.'" src="'.$current_img_url.'" onclick=\'addresseeClicked("'.$current_term_id.'", "'.$current_term_name.'", "'.$current_prettyname.'")\'>'."\n";
            else
              echo "\t\t\t\t".'<img id="scroll-recipient-id-'.$current_term_id.'" src="https://voicely.org/wp-content/themes/Avada-child/letter-composition/img/64x64.png" onclick=\'addresseeClicked("'.$current_term_id.'", "'.$current_term_name.'", "'.$current_prettyname.'")\'>'."\n";
            // Include their name label
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