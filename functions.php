<?php
/**
 * WPForge functions and definitions
 *
 * @package WPForge
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define theme constants.
define( 'WPFORGE_VERSION', '1.0.0' );
define( 'WPFORGE_DIR', get_template_directory() );
define( 'WPFORGE_URI', get_template_directory_uri() );

/**
 * Require core files.
 * This keeps functions.php clean and modules organized.
 */
$wpforge_includes = array(
	'/inc/setup.php',             // Theme setup and custom theme supports.
	'/inc/enqueue.php',           // Enqueue scripts and styles.
	'/inc/widgets.php',           // Register widget areas.
	'/inc/template-functions.php',// Custom template tags and functions.
	'/inc/customizer.php',        // Customizer additions.
    '/inc/post-types.php',        // Register custom post types and taxonomies.
    '/inc/meta-boxes.php',        // Register custom meta boxes.
	'/inc/ajax.php',              // Handle AJAX requests for filtering projects.
);

foreach ( $wpforge_includes as $file ) {
	$filepath = WPFORGE_DIR . $file;
	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	} else {
		error_log( sprintf( 'WPForge Error: Failed to load %s', $filepath ) );
	}
}