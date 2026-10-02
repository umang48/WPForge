<?php
/**
 * Enqueue theme assets.
 *
 * @package WPForge
 */

function wpforge_scripts() {
    // 1. Base theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // 2. Dynamic cache-busting for active development files
    $main_css_path = get_template_directory() . '/assets/css/main.css';
    $main_js_path  = get_template_directory() . '/assets/js/main.js';
    
    $css_version = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : $theme_version;
    $js_version  = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : $theme_version;

    // 3. Enqueue Styles
    // The core style.css (mostly for theme declaration, but good practice to load)
    wp_enqueue_style( 'wpforge-style', get_stylesheet_uri(), array(), $theme_version );
    
    // The actual design stylesheets
    wp_enqueue_style( 'wpforge-main', get_template_directory_uri() . '/assets/css/main.css', array( 'wpforge-style' ), $css_version );
    wp_enqueue_style( 'wpforge-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array( 'wpforge-main' ), $theme_version );

    // 4. Enqueue Scripts
    // true as the 5th parameter forces scripts to load in the footer (performance optimization)
    wp_enqueue_script( 'wpforge-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), $theme_version, true );
    wp_enqueue_script( 'wpforge-main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), $js_version, true );

    // 5. Securely pass PHP data to our frontend JavaScript
    // This primes the theme for the AJAX filtering and REST API fetch features.
    wp_localize_script( 'wpforge-main', 'wpforgeData', array(
        'ajax_url'   => admin_url( 'admin-ajax.php' ),
        'rest_url'   => esc_url_raw( rest_url( 'wp/v2/projects' ) ),
        'nonce'      => wp_create_nonce( 'wpforge_ajax_nonce' ),
        'error_msg'  => __( 'Something went wrong. Please try again.', 'wpforge' ),
    ) );

    // 6. Support for threaded comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'wpforge_scripts' );