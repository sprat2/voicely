<?php
// Form's HTML data'
$form_html = "
<form name='letter' method='post' onsubmit='return validateForm()'>
    " . __( 'Addressees:' ) . "
    <textarea name='addressee' cols='70'></textarea>
    <br>

    " . __( 'Title:' ) . "
    <textarea name='title' cols='70'></textarea>
    <br>
    
    " . __( 'Letter contents:' ) . "
    <textarea name='contents' cols='70'></textarea>
    <br>

    <input type='submit' value='Submit'>
</form>
";

// If form has been submitted, create the post and display sharing options
if ( ( isset ( $_POST['addressee'] ) ) &&
    ( isset ( $_POST['title'] ) ) &&
    ( isset ( $_POST['contents'] ) ) ) {
    
    // Sanitize input
    $addressee = sanitize_text_field( $_POST['addressee'] );
    $title = sanitize_text_field( $_POST['title'] );
    $contents = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['contents'] ) ) );

    // Collect addressees
    $addressees = explode( ', ', $addressee );

    // Create the post
    $post = wp_insert_post( array(
        'post_content' => $contents,
        'post_title' => $title,
        'post_status' => 'publish',
        //'post_type' => 'letter', custom types not yet supported by Avada
        'post_category' => array( get_category_by_slug( 'letter' )->term_id ),
        //'tax_input' => array(
        //    'tag' => $addressees
        //),
        'tags_input' => $addressees,
    ) );

    // Prompt the user to share their letter, and offer a link to it and other posts.
    //$sharing_box = '[fusion_sharing tagline="'.__('Your letter has been published. Share it!').'" title="'.__( 'I just wrote an open letter titled &quot;').$title.'&quot; - " link="'.get_permalink( $post ).'" description="" /]';
    //echo do_shortcode( $sharing_box );

    // Display other letters.
    //$other_letters = '[fusion_blog layout="large" blog_grid_columns="3" blog_grid_column_spacing="40" number_posts="-1" offset="0" cat_slug="letter" exclude_cats="" orderby="date" order="DESC" thumbnail="no" title="yes" title_link="yes" excerpt="yes" excerpt_length="100" strip_html="yes" meta_all="yes" meta_author="yes" meta_categories="no" meta_comments="yes" meta_date="yes" meta_link="yes" meta_tags="yes" scrolling="infinite" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="" /]';
    //echo do_shortcode( $other_letters );

    // Display sharing links and other letters
    echo do_shortcode( '[fusion_builder_container hundred_percent="no" equal_height_columns="no" menu_anchor="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="" background_color="" background_image="" background_position="center center" background_repeat="no-repeat" fade="no" background_parallax="none" enable_mobile="no" parallax_speed="0.3" video_mp4="" video_webm="" video_ogv="" video_url="" video_aspect_ratio="16:9" video_loop="yes" video_mute="yes" video_preview_image="" border_size="" border_color="" border_style="solid" margin_top="" margin_bottom="" padding_top="" padding_right="" padding_bottom="" padding_left=""][fusion_builder_row][fusion_builder_column type="1_1" layout="2_3" spacing="" center_content="no" hover_type="none" link="" min_height="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="" background_color="" background_image="" background_position="left top" background_repeat="no-repeat" border_size="0" border_color="" border_style="solid" border_position="all" padding="" dimension_margin="" animation_type="" animation_direction="left" animation_speed="0.3" animation_offset="" last="no"][fusion_sharing tagline="'.__('Your letter has been published. Share it!').'" title="'.__( 'I just wrote an open letter titled &quot;').$title.'&quot; - " link="'.get_permalink( $post ).'" description="" /][fusion_title margin_top="" margin_bottom="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="" size="1" content_align="left" style_type="default" sep_color=""]More open letters:[/fusion_title][fusion_blog layout="large alternate" blog_grid_columns="3" blog_grid_column_spacing="40" number_posts="-1" offset="0" cat_slug="letter" exclude_cats="" orderby="date" order="DESC" thumbnail="no" title="yes" title_link="yes" excerpt="yes" excerpt_length="100" strip_html="yes" meta_all="yes" meta_author="yes" meta_categories="no" meta_comments="yes" meta_date="yes" meta_link="yes" meta_tags="yes" scrolling="infinite" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="" /][/fusion_builder_column][/fusion_builder_row][/fusion_builder_container]' );
}
// If form has not yet been submitted, present it to the user
else {
    echo $form_html;
}
?>

<script>
// Validates the form, ensuring that no field is empty
function validateForm() {
    if ( ( document.forms['letter']['addressee'].value === '' ) ||
        ( document.forms['letter']['title'].value === '' ) ||
        ( document.forms['letter']['contents'].value === '' ) ) {
        return false;
    }
}
</script>