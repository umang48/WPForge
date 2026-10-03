<?php
/**
 * Register widget area.
 *
 * @package WPForge
 */

function wpforge_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Primary Sidebar', 'wpforge' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'wpforge' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s" style="margin-bottom: 40px;">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title" style="font-size: 1.2em; border-bottom: 2px solid #0073aa; padding-bottom: 10px; margin-bottom: 20px;">',
            'after_title'   => '</h2>',
        )
    );

    // Registering a Footer widget area is also a great senior-level practice
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Widgets', 'wpforge' ),
            'id'            => 'sidebar-footer',
            'description'   => esc_html__( 'Add widgets here to appear in the footer.', 'wpforge' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title" style="font-size: 1.2em; margin-bottom: 15px;">',
            'after_title'   => '</h2>',
        )
    );
}
add_action( 'widgets_init', 'wpforge_widgets_init' );