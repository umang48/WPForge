<?php
/**
 * Security Hardening & Safe Database Queries
 *
 * @package WPForge
 */

// 1. Block direct file access
defined( 'ABSPATH' ) || exit;

/**
 * Demonstrate secure raw SQL querying with $wpdb->prepare.
 * Fetches the total number of projects associated with a specific client.
 *
 * @param string $client_name The name of the client.
 * @return int Number of projects.
 */
function wpforge_get_project_count_by_client( $client_name ) {
    global $wpdb;
    
    // Use the object cache to prevent repeated DB hits
    $cache_key = 'wpforge_client_count_' . md5( $client_name );
    $count     = wp_cache_get( $cache_key );
    
    if ( false === $count ) {
        // ALWAYS use prepare() when passing variables to raw SQL to prevent SQL Injection
        $query = $wpdb->prepare(
            "SELECT COUNT(post_id) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",
            '_wpforge_project_client',
            $client_name
        );
        
        $count = $wpdb->get_var( $query );
        wp_cache_set( $cache_key, $count, '', HOUR_IN_SECONDS );
    }
    
    return (int) $count;
}

/**
 * Security Hardening: Disable XML-RPC to prevent brute force & DDoS attacks.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Security Hardening: Hide WordPress version number from scripts, styles, and meta tags.
 * Helps prevent automated vulnerability scanning.
 */
function wpforge_remove_version_strings( $src ) {
    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'wpforge_remove_version_strings', 9999 );
add_filter( 'script_loader_src', 'wpforge_remove_version_strings', 9999 );
add_filter( 'the_generator', '__return_empty_string' );