<?php
// Currently prints the top X tags as formatted by the tagsinput plugin (using their class declarations)
// $max_tags = 30;

// $args = array(
//   'orderby' => 'count',
//   'order' => 'DESC',
// );
// $tags = get_tags( $args );
 
// for ( $i=0; ($i<$max_tags) && ($i<sizeof($tags)); $i++ ) {
//   $tag = $tags[$i];
//   echo '<span id="related-tag-id-'.$tag->name.'" class="tag label label-info draggable" onclick=\'suggestedTagClicked("'.$tag->term_id.'", "'.$tag->name.'")\'>'.$tag->name.'</span>'."\n";
// }

// Hardcoded tags:
$tags = array( 'Republican', 'Republican-Values', 'Conservative', 'Conservative-Values',
  'Conservative-Agenda', 'Democrat', 'Liberal', 'Liberal-Values', 'Liberal-Agenda',
  'Abortion', 'Budget-and-Spending', 'Civil-Liberties', 'Crime-and-Safety',
  'Economy-and-Jobs', 'Education', 'Energy', 'Environment', 'Foreign-Policy',
  'Gay-Marriage', 'Guns', 'Health-Care', 'Immigration', 'Medicare-and-Social-Security',
  'National-Security', 'Taxes', 'Veterans' );

shuffle( $tags );

for ( $i=0; ($i<sizeof($tags)); $i++ ) {
  $tag = $tags[$i];
  echo '<span id="related-tag-id-'.$tag.'" class="tag label label-info draggable" onclick=\'suggestedTagClicked("unknownID", "'.$tag.'")\'>'.$tag.'</span>'."\n";
}

?>