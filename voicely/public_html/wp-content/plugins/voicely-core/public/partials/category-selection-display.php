<?php

/**
 * Given a starting parent taxonomy, narrows down the user's selection
 *   of taxonomy until the resulting taxonomy is returned as GET parameter:
 *   "?$taxonomy=term_id"
 *
 * Selection is currently the last element without children, or a person
 *
 *
 * @since   1.0.0
 */
function generate_display_given_head( $head, $taxonomy, $given_selection_type ) {

    $unselectable_color = '#eee';
    $selectable_color = '#ccc';

    switch ( $given_selection_type ) {
        case 'people':
            $selection_type = 1;
            break;
        case 'topics':
            $selection_type = 2;
            break;
        default:
            return 'Invalid selection condition';
            break;
    }

    // If the head category exists, get its children
    if ( term_exists( $head, $taxonomy ) ) {

        // Set term ID to the given parent category
        $term_id = get_cat_ID( urldecode( $head ) );

        // Get the children of this term
        $children = get_categories ( 
            array(
                'parent' => $term_id,
                'hide_empty' => 0,
                'orderby' => 'name',
                'order' => 'ASC'
            )
        );

        // Initialize the returned display html code
        $return_code = '[fusion_flip_boxes columns="5" hide_on_mobile="0" class="" id="category-boxes"]';

        // Create the elements for each child
        foreach ($children as $child) {
            // Child values:
            //   term_id, name, slug, term_group, term_taxonomy_id, 
            //   taxonomy, description, parent, count, filter, cat_ID, 
            //   category_count, category_description, cat_name, 
            //   category_nicename, category_parent

            // If we display any children, mark a variable to remember this later
            $children_exist = 'variable is set';
            
            // Get the children of this term
            $subchildren = get_categories ( 
                array(
                    'parent' => $child->cat_ID,
                    'hide_empty' => 0,
                    'orderby' => 'name',
                    'order' => 'ASC'
                )
            );

            // Proceed depending on specified selection condition
            switch ( $selection_type ) {
                case 1: // person selection

                    // Only allow categories to be expanded if they have people to select within them
                    // Note: People must be assigned to the entire branch down to their leaf node.
                    // Note: Doesn't check for posts in the people category - which is a structure violation
                    $populated = ( $child->count > 0 );

                    // Generate the code for this item
                    $return_code .= $populated ? '<a href=.?selection_' . $given_selection_type . '=' . urlencode( $child->name ) . '&selecting=' . $given_selection_type . '>' : '';
                    $return_code .= '<div id="box-for-category-flip-card">';                            
                    $return_code .= '[fusion_flip_box title_front="' . $child->name . '" title_back="' . $child->name . '" text_front="" background_color_front="' . $unselectable_color. '" title_front_color="" text_front_color="" background_color_back="" title_back_color="" text_back_color="" border_size="" border_color="" border_radius="" icon="" icon_color="" circle="" circle_color="" circle_border_color="" icon_flip="" icon_rotate="" icon_spin="" image="" image_width="" image_height="" animation_offset="" animation_type="" animation_direction="" animation_speed=""]' . (sizeof( $subchildren ) + $child->count) . ' more[/fusion_flip_box]';
                    $return_code .= '</div>';
                    $return_code .= $populated ? '</a>' : '';
                    break;
                case 2: // no-children selection
                    // Generate the code for this item if it has children
                    if ( sizeof( $subchildren ) > 0 ) {
                        $return_code .= '<a href=.?selection_' . $given_selection_type . '=' . urlencode( $child->name ) . '&selecting=' . $given_selection_type . '>';
                        $return_code .= '<div id="box-for-category-flip-card">';                            
                        $return_code .= '[fusion_flip_box title_front="' . $child->name . '" title_back="' . $child->name . '" text_front="" background_color_front="' . $unselectable_color. '" title_front_color="" text_front_color="" background_color_back="" title_back_color="" text_back_color="" border_size="" border_color="" border_radius="" icon="" icon_color="" circle="" circle_color="" circle_border_color="" icon_flip="" icon_rotate="" icon_spin="" image="" image_width="" image_height="" animation_offset="" animation_type="" animation_direction="" animation_speed=""]' . sizeof( $subchildren ) . ' more[/fusion_flip_box]';
                        $return_code .= '</div>';
                        $return_code .= '</a>';
                    }
                    // If the subterm has no children, generate a selecting link, rather than a refining one
                    else {
                        $return_code .= '<a href=.?selection_' . $given_selection_type . '=' . urlencode( $child->name ) . '>';
                        $return_code .= '<div id="box-for-category-flip-card">';                            
                        $return_code .= '[fusion_flip_box title_front="' . $child->name . '" title_back="' . $child->name . '" text_front="" background_color_front="' . $selectable_color. '" title_front_color="" text_front_color="" background_color_back="" title_back_color="" text_back_color="" border_size="" border_color="" border_radius="" icon="" icon_color="" circle="" circle_color="" circle_border_color="" icon_flip="" icon_rotate="" icon_spin="" image="" image_width="" image_height="" animation_offset="" animation_type="" animation_direction="" animation_speed=""][/fusion_flip_box]';
                        $return_code .= '</div>';
                        $return_code .= '</a>';
                    }
                    break;
                default: // error
                    return 'Invalid selection_type';
                    break;
            }
        }

        // Include additional (non-category) selectable elements if category has no more children
        //  (or if fewer than 10 exist)
        if ( !isset( $children_exist ) || ( get_category( $term_id )->count < 10 ) ) {
            // Ensure we're selecting people
            switch ( $selection_type ) {
                case 1: // person selection
                    $the_query = new WP_Query( array( 
                        'post_type'=> 'people',
                        'post_status'=> 'publish',
                        'order'=> 'ASC',
                        'orderby'=> 'name',
                        'cat'=> $term_id,
                    ) );
                    
                    // Display UI elements for each person
                    if( $the_query->have_posts() ) {
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post(); // load & iterate

                            // the_title();
                            // the_meta();
                            // echo get_post_meta( get_the_ID(), 'META_KEY', true );

                            $phone_num = get_post_meta( get_the_ID(), 'phone_number', true );
                            // Unneeded now that numbers are stored as strings with orig. formatting
                            // $phone_num = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~',
                            //    '($1) $2-$3',
                            //    $phone_num);

                            //$front_of_card = get_post_meta( get_the_ID(), 'first_name', true ) . 
                            //    ' ' . get_post_meta( get_the_ID(), 'last_name', true );
                            $front_of_card = get_the_title();
                            
                            $back_of_card = '<div id="back-of-person-card-text">' . 
                                '<b>' . get_post_meta( get_the_ID(), 'first_name', true ) . 
                                ' ' . get_post_meta( get_the_ID(), 'middle_name', true ) .
                                ' ' . get_post_meta( get_the_ID(), 'last_name', true ) .
                                '</b><br>' . get_post_meta( get_the_ID(), 'address', true ) . 
                                '<br>' . $phone_num . '</div>';
                            
                            $return_code .= '<a href=.?selection_' . $given_selection_type . '=' . get_the_ID() . '>';
                            $return_code .= '<div id="box-for-person-flip-card">';                            
                            $return_code .= '[fusion_flip_box title_front="' . $front_of_card . '" title_back="" text_front="" background_color_front="' . $selectable_color. '" title_front_color="" text_front_color="" background_color_back="" title_back_color="" text_back_color="" border_size="" border_color="" border_radius="" icon="" icon_color="" circle="" circle_color="" circle_border_color="" icon_flip="" icon_rotate="" icon_spin="" image="" image_width="" image_height="" animation_offset="" animation_type="" animation_direction="" animation_speed=""]' . $back_of_card . '[/fusion_flip_box]';
                            $return_code .= '</div>';
                            $return_code .= '</a>';
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        // Finalize and return the display html code
        $return_code .= '[/fusion_flip_boxes]';
        return $return_code;
    }
    else {
        return 'Incorrect head category.';
    }
}