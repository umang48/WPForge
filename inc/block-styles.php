<?php
/**
 * Custom Gutenberg Block Styles
 *
 * @package WPForge
 */

function wpforge_register_block_styles() {
    // Ensure the function exists (failsafe for older WP versions)
    if ( ! function_exists( 'register_block_style' ) ) {
        return;
    }

    // 1. Group Block: "Card Panel"
    register_block_style(
        'core/group',
        array(
            'name'         => 'wpforge-card-panel',
            'label'        => __( 'Card Panel', 'wpforge' ),
            'is_default'   => false,
        )
    );

    // 2. Button Block: "Arrow Button"
    register_block_style(
        'core/button',
        array(
            'name'         => 'wpforge-arrow-button',
            'label'        => __( 'Arrow Button', 'wpforge' ),
            'is_default'   => false,
        )
    );

    // 3. Image Block: "Subtle Shadow"
    register_block_style(
        'core/image',
        array(
            'name'         => 'wpforge-subtle-shadow',
            'label'        => __( 'Subtle Shadow', 'wpforge' ),
            'is_default'   => false,
        )
    );
}
add_action( 'init', 'wpforge_register_block_styles' );