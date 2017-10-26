<?php
// Currently prints the top X tags as formatted by the tagsinput plugin (using their class declarations)
$max_tags = 30;

$args = array(
  'orderby' => 'count',
  'order' => 'DESC',
);
$tags = get_tags( $args );
 
for ( $i=0; ($i<$max_tags) && ($i<sizeof($tags)); $i++ ) {
  $tag = $tags[$i];
  echo '<span id="related-tag-id-'.$tag->term_id.'" class="tag label label-info" onclick=\'suggestedTagClicked("'.$tag->term_id.'", "'.$tag->name.'")\'>'.$tag->name.'</span>'."\n";
}
?>