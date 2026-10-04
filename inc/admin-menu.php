<?php
/**
 * Top-level Admin Menu & Theme Settings
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Register the Menu Pages
 */
function wpforge_register_admin_menu() {
    add_menu_page(
        __( 'WPForge Settings', 'wpforge' ),
        __( 'WPForge', 'wpforge' ),
        'manage_options',
        'wpforge-options',
        'wpforge_general_settings_page',
        'dashicons-layout',
        59
    );
    
    add_submenu_page(
        'wpforge-options',
        __( 'General Settings', 'wpforge' ),
        __( 'General', 'wpforge' ),
        'manage_options',
        'wpforge-options',
        'wpforge_general_settings_page'
    );
}
add_action( 'admin_menu', 'wpforge_register_admin_menu' );

/**
 * 2. Register Settings API fields
 */
function wpforge_register_settings() {
    // Register a single option array in the database to keep the wp_options table clean
    register_setting(
        'wpforge_settings_group',     // Option group
        'wpforge_theme_options',      // Option name in database
        'wpforge_sanitize_options'    // Sanitization callback
    );

    // Add a section to our settings page
    add_settings_section(
        'wpforge_general_section',
        __( 'Global Configuration', 'wpforge' ),
        'wpforge_general_section_callback',
        'wpforge-options'
    );

    // Add Google Analytics ID Field
    add_settings_field(
        'wpforge_ga_id',
        __( 'Google Analytics Measurement ID', 'wpforge' ),
        'wpforge_ga_id_render',
        'wpforge-options',
        'wpforge_general_section'
    );

    // Add Custom Footer Text Field
    add_settings_field(
        'wpforge_footer_text',
        __( 'Custom Footer Copyright Text', 'wpforge' ),
        'wpforge_footer_text_render',
        'wpforge-options',
        'wpforge_general_section'
    );
}
add_action( 'admin_init', 'wpforge_register_settings' );

/**
 * 3. Sanitization Callback (Security)
 */
function wpforge_sanitize_options( $input ) {
    $sanitized = array();
    if ( isset( $input['ga_id'] ) ) {
        $sanitized['ga_id'] = sanitize_text_field( $input['ga_id'] );
    }
    if ( isset( $input['footer_text'] ) ) {
        // wp_kses_post allows basic HTML like links (<a>), but strips dangerous scripts
        $sanitized['footer_text'] = wp_kses_post( $input['footer_text'] );
    }
    return $sanitized;
}

/**
 * 4. Field Rendering Callbacks
 */
function wpforge_general_section_callback() {
    echo '<p>' . esc_html__( 'Configure your tracking scripts and global text overrides.', 'wpforge' ) . '</p>';
}

function wpforge_ga_id_render() {
    $options = get_option( 'wpforge_theme_options' );
    $ga_id   = isset( $options['ga_id'] ) ? $options['ga_id'] : '';
    ?>
    <input type="text" name="wpforge_theme_options[ga_id]" value="<?php echo esc_attr( $ga_id ); ?>" placeholder="G-XXXXXXXXXX" class="regular-text">
    <p class="description"><?php esc_html_e( 'Enter your GA4 Measurement ID to automatically inject the tracking script into the header.', 'wpforge' ); ?></p>
    <?php
}

function wpforge_footer_text_render() {
    $options     = get_option( 'wpforge_theme_options' );
    $footer_text = isset( $options['footer_text'] ) ? $options['footer_text'] : '';
    ?>
    <textarea name="wpforge_theme_options[footer_text]" rows="4" class="large-text code"><?php echo esc_textarea( $footer_text ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Overrides the default copyright text. Basic HTML like <a> tags are allowed.', 'wpforge' ); ?></p>
    <?php
}

/**
 * 5. Render the Settings Page HTML
 */
function wpforge_general_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'WPForge General Settings', 'wpforge' ); ?></h1>
        
        <?php settings_errors(); // Displays success/error messages automatically ?>
        
        <form action="options.php" method="post" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; margin-top: 20px; max-width: 800px;">
            <?php
            // Output security fields for the registered setting
            settings_fields( 'wpforge_settings_group' );
            
            // Output setting sections and their fields
            do_settings_sections( 'wpforge-options' );
            
            // Output save settings button
            submit_button( __( 'Save Configuration', 'wpforge' ) );
            ?>
        </form>
    </div>
    <?php
}

/**
 * 6. Inject Google Analytics into the Frontend Header
 */
function wpforge_inject_analytics() {
    $options = get_option( 'wpforge_theme_options' );
    if ( ! empty( $options['ga_id'] ) ) {
        $ga_id = esc_attr( $options['ga_id'] );
        ?>
        <!-- Google Analytics injected via WPForge Settings -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_id; ?>"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '<?php echo $ga_id; ?>');
        </script>
        <?php
    }
}
add_action( 'wp_head', 'wpforge_inject_analytics', 20 );