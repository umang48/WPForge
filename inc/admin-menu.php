<?php
/**
 * Top-level Admin Menu for Theme Settings
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

function wpforge_register_admin_menu() {
    // 1. Register the Top-Level Menu
    add_menu_page(
        __( 'WPForge Settings', 'wpforge' ), // Page title
        __( 'WPForge', 'wpforge' ),          // Menu title
        'manage_options',                    // Capability
        'wpforge-options',                   // Menu slug
        'wpforge_general_settings_page',     // Callback function
        'dashicons-layout',                  // Icon
        59                                   // Position (below Appearance)
    );
    
    // 2. Register the default submenu (General Settings)
    add_submenu_page(
        'wpforge-options',                   // Parent slug
        __( 'General Settings', 'wpforge' ),
        __( 'General', 'wpforge' ),
        'manage_options',
        'wpforge-options',                   // Same slug as parent overrides the default duplicate name
        'wpforge_general_settings_page'
    );
}
add_action( 'admin_menu', 'wpforge_register_admin_menu' );

/**
 * Render the General Settings Page (Placeholder for future theme options)
 */
function wpforge_general_settings_page() {
    // Security check
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'WPForge Theme Settings', 'wpforge' ); ?></h1>
        <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; margin-top: 20px; max-width: 800px;">
            <h2><?php esc_html_e( 'Welcome to WPForge', 'wpforge' ); ?></h2>
            <p><?php esc_html_e( 'Use the submenus on the left to import demo data or configure advanced theme options.', 'wpforge' ); ?></p>
            <p><em><?php esc_html_e( '(Settings API fields like custom API keys, header scripts, or layout toggles can be added here.)', 'wpforge' ); ?></em></p>
        </div>
    </div>
    <?php
}