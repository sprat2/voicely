<?php

// Begin setup
function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );
// End setup


// Override letters' meta display
//   Function is modeled after (and overrides) the one in /includes/lib/inc/functions.php
//   Note: 'fusion_post_metadata_markup' is a filter on the HTML for the entire meta-element.
//         This may be a good alternative where navigating the HTML seems prefereble.
function fusion_render_post_metadata( $layout, $settings = array() ) {

	$html = $author = $date = $metadata = '';

	$settings = ( is_array( $settings ) ) ? $settings : array();

	$default_settings = array(
		'post_meta'          => fusion_library()->get_option( 'post_meta' ),
		'post_meta_author'   => fusion_library()->get_option( 'post_meta_author' ),
		'post_meta_date'     => fusion_library()->get_option( 'post_meta_date' ),
		'post_meta_cats'     => fusion_library()->get_option( 'post_meta_cats' ),
		'post_meta_tags'     => fusion_library()->get_option( 'post_meta_tags' ),
		'post_meta_comments' => fusion_library()->get_option( 'post_meta_comments' ),
	);

	$settings = wp_parse_args( $settings, $default_settings );
	$post_meta = get_post_meta( get_queried_object_id(), 'pyre_post_meta', true );

	// Check if meta data is enabled.
	if ( ( $settings['post_meta'] && 'no' !== $post_meta ) || ( ! $settings['post_meta'] && 'yes' === $post_meta ) ) {

		// For alternate, grid and timeline layouts return empty single-line-meta if all meta data for that position is disabled.
		if ( in_array( $layout, array( 'alternate', 'grid_timeline' ), true ) && ! $settings['post_meta_author'] && ! $settings['post_meta_date'] && ! $settings['post_meta_cats'] && ! $settings['post_meta_tags'] && ! $settings['post_meta_comments'] ) {
			return '';
		}

		// Render author meta data.
		if ( $settings['post_meta_author'] ) {
			ob_start();
			the_author_posts_link();
			$author_post_link = ob_get_clean();

			// Check if rich snippets are enabled.
			if ( ! fusion_library()->get_option( 'disable_date_rich_snippet_pages' ) ) {
				$metadata .= sprintf( esc_html__( 'By %s', 'Avada' ), '<span>' . $author_post_link . '</span>' );
			} else {
				$metadata .= sprintf( esc_html__( 'By %s', 'Avada' ), '<span class="vcard"><span class="fn">' . $author_post_link . '</span></span>' );
			}
			$metadata .= '<span class="fusion-inline-sep">|</span>';
		} else { // If author meta data won't be visible, render just the invisible author rich snippet.
			$author .= fusion_render_rich_snippets_for_pages( false, true, false );
		}

		// Render the updated meta data or at least the rich snippet if enabled.
		if ( $settings['post_meta_date'] ) {
			$metadata .= fusion_render_rich_snippets_for_pages( false, false, true );

			$formatted_date = get_the_time( fusion_library()->get_option( 'date_format' ) );
			$date_markup = '<span>' . $formatted_date . '</span><span class="fusion-inline-sep">|</span>';
			$metadata .= apply_filters( 'fusion_post_metadata_date', $date_markup, $formatted_date );
		} else {
			$date .= fusion_render_rich_snippets_for_pages( false, false, true );
		}

		// Render rest of meta data.
		// Render addressees if the post is a letter.		
		if ( get_post_type() == 'letter' ) {
			$addressees = get_the_term_list( get_the_ID(), 'addressee', '', ', ' );
			if ( $addressees ) {
				$metadata .= sprintf( esc_html__( 'Addressees: %s', 'Avada' ), $addressees );
				$metadata .= '<span class="fusion-inline-sep">|</span>';
			}
		}

		// Render categories.
		if ( $settings['post_meta_cats'] ) {
			ob_start();
			the_category( ', ' );
			$categories = ob_get_clean();

			if ( $categories ) {
				$metadata .= ( $settings['post_meta_tags'] ) ? sprintf( esc_html__( 'Categories: %s', 'Avada' ), $categories ) : $categories;
				$metadata .= '<span class="fusion-inline-sep">|</span>';
			}
		}

		// Render tags.
		if ( $settings['post_meta_tags'] ) {
			ob_start();
			the_tags( '' );
			$tags = ob_get_clean();

			if ( $tags ) {
				$metadata .= '<span class="meta-tags">' . sprintf( esc_html__( 'Tags: %s', 'Avada' ), $tags ) . '</span><span class="fusion-inline-sep">|</span>';
			}
		}

		// Render comments.
		if ( $settings['post_meta_comments'] && 'grid_timeline' !== $layout ) {
			ob_start();
			comments_popup_link( esc_html__( '0 Comments', 'Avada' ), esc_html__( '1 Comment', 'Avada' ), esc_html__( '% Comments', 'Avada' ) );
			$comments = ob_get_clean();
			$metadata .= '<span class="fusion-comments">' . $comments . '</span>';
		}

		// Render the HTML wrappers for the different layouts.
		if ( $metadata ) {
			$metadata = $author . $date . $metadata;

			if ( 'single' === $layout ) {
				$html .= '<div class="fusion-meta-info"><div class="fusion-meta-info-wrapper">' . $metadata . '</div></div>';
			} elseif ( in_array( $layout, array( 'alternate', 'grid_timeline' ), true ) ) {
				$html .= '<p class="fusion-single-line-meta">' . $metadata . '</p>';
			} else {
				$html .= '<div class="fusion-alignleft">' . $metadata . '</div>';
			}
		} else {
			$html .= $author . $date;
		}
	} else {
		// Render author and updated rich snippets for grid and timeline layouts.
		if ( fusion_library()->get_option( 'disable_date_rich_snippet_pages' ) ) {
			$html .= fusion_render_rich_snippets_for_pages( false );
		}
	}// End if().

	return apply_filters( 'fusion_post_metadata_markup', $html );
}

// Add letters to the main loop of archive pages so they'll be included in queries by their metadata
function add_letters_to_archive_pages($query) {
    if ( !is_admin() && $query->is_main_query() && $query->is_archive() ) {
        $query->set('post_type', array( 'post', 'letter' ));
    }
}
add_action('pre_get_posts', 'add_letters_to_archive_pages');

// Add letters to the main loop of search pages so they'll be included in search queries
//     	Overriding behavior of:
//			'search_filter' in includes/class-avada-blog.php, and
// 			'modify_search_filter' in includes/class-avada-init.php
function add_letters_to_search_pages($query) {
    if ( !is_admin() && $query->is_main_query() && $query->is_search() && is_search() ) {
        $query->set('post_type', array( 'post', 'letter' ));
    }
}
add_action('pre_get_posts', 'add_letters_to_search_pages');