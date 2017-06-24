<?php

// Import Avada's style sheet before this child theme's style sheet (and cache-bust)
function my_theme_enqueue_styles() {
    $parent_style = 'parent-style'; // More efficient (yet more error-prone) if replaced per https://codex.wordpress.org/Child_Themes
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );