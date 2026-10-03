<?php
/**
 * Performance Optimization & Bloat Removal
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

function wpforge_clean_head_bloat() {
    // 1. Remove native emoji scripts and styles (saves ~2 HTTP requests)
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    
    // 2. Remove Windows Live Writer and RSD links (Legacy APIs)
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    
    // 3. Remove shortlink tag
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
    
    // 4. Remove REST API link from head (API still works, just removes the discovery tag)
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
add_action( 'init', 'wpforge_clean_head_bloat' );

/**
 * Disable Block Editor core patterns.
 * Prevents WP from downloading hundreds of unused remote patterns on the backend, speeding up the editor.
 */
add_action( 'after_setup_theme', function() {
    remove_theme_support( 'core-block-patterns' );
} );