<?php
/**
 * Top-level Admin Menu & Theme Settings
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Register the Menu Pages (Centralized)
 */
function wpforge_register_admin_menu() {
    // Parent Menu
    add_menu_page(
        __( 'WPForge Settings', 'wpforge' ),
        __( 'WPForge', 'wpforge' ),
        'manage_options',
        'wpforge-options',
        'wpforge_general_settings_page',
        'dashicons-code-standards',
        59
    );
    
    // Submenu 1: General Settings (Uses same slug as parent)
    add_submenu_page(
        'wpforge-options',
        __( 'General Settings', 'wpforge' ),
        __( 'General Settings', 'wpforge' ),
        'manage_options',
        'wpforge-options',
        'wpforge_general_settings_page'
    );

    // Submenu 2: Demo Importer
    add_submenu_page(
        'wpforge-options',
        __( 'Demo Importer', 'wpforge' ),
        __( 'Demo Importer', 'wpforge' ),
        'manage_options',
        'wpforge-demo-importer',
        'wpforge_demo_page_html' // This callback lives in demo-importer.php
    );
}
add_action( 'admin_menu', 'wpforge_register_admin_menu' );

// Enqueue WordPress Media Uploader and Color Picker scripts
function wpforge_admin_scripts( $hook ) {
    if ( $hook !== 'toplevel_page_wpforge-options' ) return;
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'wpforge_admin_scripts' );

function wpforge_register_settings() {
    register_setting( 'wpforge_settings_group', 'wpforge_theme_options', 'wpforge_sanitize_options' );

    add_settings_section( 'wpforge_general_section', '', '__return_empty_string', 'wpforge-options' );

    add_settings_field(
        'wpforge_ga_id',
        __( 'Google Analytics ID', 'wpforge' ),
        'wpforge_ga_id_render',
        'wpforge-options',
        'wpforge_general_section'
    );

    add_settings_field(
        'wpforge_footer_text',
        __( 'Footer Copyright Text', 'wpforge' ),
        'wpforge_footer_text_render',
        'wpforge-options',
        'wpforge_general_section'
    );


    // Global Settings
    add_settings_section( 'wpforge_design_section', __( 'Design & Branding', 'wpforge' ), '__return_empty_string', 'wpforge-options' );
    
    add_settings_field( 'wpforge_logo', __( 'Custom Logo URL', 'wpforge' ), 'wpforge_logo_render', 'wpforge-options', 'wpforge_design_section' );
    add_settings_field( 'wpforge_color', __( 'Primary Brand Color', 'wpforge' ), 'wpforge_color_render', 'wpforge-options', 'wpforge_design_section' );

    // Homepage Section Toggles
    add_settings_section( 'wpforge_home_section', __( 'Homepage Sections (Show/Hide)', 'wpforge' ), '__return_empty_string', 'wpforge-options' );
    
    add_settings_field( 'wpforge_show_projects', __( 'Show Recent Projects', 'wpforge' ), 'wpforge_toggle_render', 'wpforge-options', 'wpforge_home_section', array( 'id' => 'show_projects' ) );
    add_settings_field( 'wpforge_show_team', __( 'Show Team Section', 'wpforge' ), 'wpforge_toggle_render', 'wpforge-options', 'wpforge_home_section', array( 'id' => 'show_team' ) );
    add_settings_field( 'wpforge_show_testimonials', __( 'Show Testimonials', 'wpforge' ), 'wpforge_toggle_render', 'wpforge-options', 'wpforge_home_section', array( 'id' => 'show_testimonials' ) );

    // Contact Form Settings Section
    add_settings_section( 'wpforge_contact_section', __( 'Contact Form Routing', 'wpforge' ), '__return_empty_string', 'wpforge-options' );
    
    add_settings_field( 'wpforge_contact_email', __( 'Recipient Email Address', 'wpforge' ), 'wpforge_contact_email_render', 'wpforge-options', 'wpforge_contact_section' );
    add_settings_field( 'wpforge_contact_msg', __( 'Success Message', 'wpforge' ), 'wpforge_contact_msg_render', 'wpforge-options', 'wpforge_contact_section' );

}
add_action( 'admin_init', 'wpforge_register_settings' );

function wpforge_sanitize_options( $input ) {
    $sanitized = array();
    
    // Existing fields
    if ( isset( $input['ga_id'] ) ) $sanitized['ga_id'] = sanitize_text_field( $input['ga_id'] );
    if ( isset( $input['footer_text'] ) ) $sanitized['footer_text'] = wp_kses_post( $input['footer_text'] );
    if ( isset( $input['logo'] ) ) $sanitized['logo'] = esc_url_raw( $input['logo'] );
    if ( isset( $input['primary_color'] ) ) $sanitized['primary_color'] = sanitize_hex_color( $input['primary_color'] );
    
    // Toggles
    if ( isset( $input['show_projects'] ) ) $sanitized['show_projects'] = '1';
    if ( isset( $input['show_team'] ) ) $sanitized['show_team'] = '1';
    if ( isset( $input['show_testimonials'] ) ) $sanitized['show_testimonials'] = '1';

    // NEW: Contact Fields
    if ( isset( $input['contact_email'] ) ) {
        $sanitized['contact_email'] = sanitize_email( $input['contact_email'] );
    }
    if ( isset( $input['contact_msg'] ) ) {
        $sanitized['contact_msg'] = sanitize_text_field( $input['contact_msg'] );
    }
    
    return $sanitized;
}

function wpforge_ga_id_render() {
    $options = get_option( 'wpforge_theme_options' );
    $ga_id   = isset( $options['ga_id'] ) ? $options['ga_id'] : '';
    echo '<input type="text" name="wpforge_theme_options[ga_id]" value="' . esc_attr( $ga_id ) . '" placeholder="G-XXXXXXXXXX" class="regular-text">';
    echo '<p class="description">' . esc_html__( 'Enter your GA4 Measurement ID to inject tracking scripts into the header.', 'wpforge' ) . '</p>';
}

function wpforge_footer_text_render() {
    $options     = get_option( 'wpforge_theme_options' );
    $footer_text = isset( $options['footer_text'] ) ? $options['footer_text'] : '';
    echo '<textarea name="wpforge_theme_options[footer_text]" rows="4" class="large-text code">' . esc_textarea( $footer_text ) . '</textarea>';
    echo '<p class="description">' . esc_html__( 'Basic HTML like <a> tags are allowed.', 'wpforge' ) . '</p>';
}


// 1. Logo Field with Media Uploader
function wpforge_logo_render() {
    $options = get_option( 'wpforge_theme_options' );
    $val = isset( $options['logo'] ) ? $options['logo'] : '';
    echo '<input type="text" id="wpforge_logo_url" name="wpforge_theme_options[logo]" value="' . esc_attr( $val ) . '" class="regular-text">';
    echo ' <input type="button" id="wpforge_logo_button" class="button" value="' . __( 'Upload Image', 'wpforge' ) . '">';
    ?>
    <script>
    jQuery(document).ready(function($){
        $('#wpforge_logo_button').click(function(e) {
            e.preventDefault();
            var image = wp.media({ title: 'Upload Logo', multiple: false }).open().on('select', function(e){
                var uploaded_image = image.state().get('selection').first();
                var image_url = uploaded_image.toJSON().url;
                $('#wpforge_logo_url').val(image_url);
            });
        });
        $('.wpforge-color-picker').wpColorPicker();
    });
    </script>
    <?php
}

// 2. Color Field
function wpforge_color_render() {
    $options = get_option( 'wpforge_theme_options' );
    $val = isset( $options['primary_color'] ) ? $options['primary_color'] : '#0073aa';
    echo '<input type="text" name="wpforge_theme_options[primary_color]" value="' . esc_attr( $val ) . '" class="wpforge-color-picker">';
}

// 3. Dynamic Toggle Field
function wpforge_toggle_render( $args ) {
    $options = get_option( 'wpforge_theme_options' );
    $id = $args['id'];
    $checked = isset( $options[$id] ) && $options[$id] === '1' ? 'checked' : '';
    echo '<input type="checkbox" name="wpforge_theme_options[' . esc_attr( $id ) . ']" value="1" ' . $checked . '>';
}

/**
 * Render the Premium Settings Page HTML
 */
function wpforge_general_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    ?>
    <div class="wrap wpforge-admin-wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'WPForge Configuration', 'wpforge' ); ?></h1>
        <hr class="wp-header-end">
        
        <?php settings_errors(); ?>
        
        <!-- Premium Header Banner -->
        <div class="wpforge-admin-header" style="background: #0073aa; color: #fff; padding: 25px; border-radius: 6px; margin: 20px 0; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <div>
                <h2 style="color: #fff; margin: 0; font-size: 1.6em; border: none; padding: 0;"><?php esc_html_e( 'Welcome to WPForge Agency Theme', 'wpforge' ); ?></h2>
                <p style="margin: 8px 0 0; font-size: 1.1em; opacity: 0.9;"><?php esc_html_e( 'Manage global configurations, tracking scripts, and demo content imports.', 'wpforge' ); ?></p>
            </div>
            <div>
                <span class="dashicons dashicons-wordpress" style="font-size: 50px; width: 50px; height: 50px; opacity: 0.8;"></span>
            </div>
        </div>

        <!-- WordPress Native 2-Column Layout -->
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                
                <!-- Left Column (Settings Form) -->
                <div id="post-body-content">
                    <form action="options.php" method="post" class="postbox" style="border-radius: 6px; overflow: hidden;">
                        <div class="postbox-header" style="background: #f6f7f7; border-bottom: 1px solid #c3c4c7; padding: 15px 20px;">
                            <h2 style="margin: 0; font-size: 1.2em;"><?php esc_html_e( 'Global Settings', 'wpforge' ); ?></h2>
                        </div>
                        <div class="inside" style="padding: 0 20px 20px;">
                            <?php 
                            settings_fields( 'wpforge_settings_group' );
                            do_settings_sections( 'wpforge-options' ); 
                            submit_button( __( 'Save Configuration', 'wpforge' ), 'primary large' ); 
                            ?>
                        </div>
                    </form>
                </div>

                <!-- Right Column (Info/Help Box) -->
                <div id="postbox-container-1" class="postbox-container">
                    <div class="postbox" style="border-radius: 6px; overflow: hidden;">
                        <div class="postbox-header" style="background: #f6f7f7; border-bottom: 1px solid #c3c4c7; padding: 15px 20px;">
                            <h2 style="margin: 0; font-size: 1.2em;"><?php esc_html_e( 'Quick Links', 'wpforge' ); ?></h2>
                        </div>
                        <div class="inside" style="padding: 20px;">
                            <p style="margin-top:0; font-size:14px; color:#555; line-height: 1.6;">
                                <?php esc_html_e( 'Starting a new build? Use the Demo Importer to scaffold your custom post types with real data automatically.', 'wpforge' ); ?>
                            </p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpforge-demo-importer' ) ); ?>" class="button button-secondary" style="width: 100%; text-align: center; margin-bottom: 15px;">
                                <?php esc_html_e( 'Run Demo Importer', 'wpforge' ); ?> &rarr;
                            </a>
                            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                            <p style="margin-bottom:0; font-size:12px; color:#888;">
                                <?php esc_html_e( 'WPForge Architecture: Custom PHP, REST APIs, Vanilla JS, and modern theme.json integration.', 'wpforge' ); ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div><!-- .columns-2 -->
        </div><!-- #poststuff -->
        
    </div><!-- .wrap -->
    <?php
}

// Inject Analytics
function wpforge_inject_analytics() {
    $options = get_option( 'wpforge_theme_options' );
    if ( ! empty( $options['ga_id'] ) ) {
        $ga_id = esc_attr( $options['ga_id'] );
        echo "<!-- Google Analytics -->\n<script async src='https://www.googletagmanager.com/gtag/js?id={$ga_id}'></script>\n<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '{$ga_id}');</script>\n";
    }
}
add_action( 'wp_head', 'wpforge_inject_analytics', 20 );

function wpforge_apply_custom_styles() {
    $options = get_option( 'wpforge_theme_options' );
    if ( ! empty( $options['primary_color'] ) ) {
        $color = esc_attr( $options['primary_color'] );
        // Override the theme.json CSS variable globally
        echo "<style>:root { --wp--preset--color--primary: {$color} !important; }</style>\n";
    }
}
add_action( 'wp_head', 'wpforge_apply_custom_styles', 99 );

// Contact Email Field
function wpforge_contact_email_render() {
    $options = get_option( 'wpforge_theme_options' );
    $val = isset( $options['contact_email'] ) ? $options['contact_email'] : get_option('admin_email');
    echo '<input type="email" name="wpforge_theme_options[contact_email]" value="' . esc_attr( $val ) . '" class="regular-text">';
    echo '<p class="description">' . esc_html__( 'The email address that will receive submissions from the custom contact page template. Defaults to site admin.', 'wpforge' ) . '</p>';
}

// Contact Success Message Field
function wpforge_contact_msg_render() {
    $options = get_option( 'wpforge_theme_options' );
    $val = isset( $options['contact_msg'] ) ? $options['contact_msg'] : __( 'Thank you! Your message has been sent successfully.', 'wpforge' );
    echo '<input type="text" name="wpforge_theme_options[contact_msg]" value="' . esc_attr( $val ) . '" class="large-text">';
}