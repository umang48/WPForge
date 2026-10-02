<?php
/**
 * Theme setup and custom theme supports.
 *
 * @package WPForge
 */

if ( ! function_exists( 'wpforge_setup' ) ) :
	function wpforge_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'wpforge', WPFORGE_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );
		
		// Custom image sizes for the portfolio grid
		add_image_size( 'wpforge-project-grid', 800, 600, true );

		// Register Navigation Menus.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary Menu', 'wpforge' ),
				'footer' => esc_html__( 'Footer Menu', 'wpforge' ),
			)
		);

		// Switch default core markup to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for core custom logo.
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Gutenberg Alignments & Editor Styles
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );
	}
endif;
add_action( 'after_setup_theme', 'wpforge_setup' );