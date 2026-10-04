<?php
/**
 * Programmatic Demo Data Importer
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Add the menu page under the WPForge custom menu
 */
function wpforge_register_demo_page() {
    add_submenu_page(
        'wpforge-options',                   
        __( 'Demo Importer', 'wpforge' ),
        __( 'Demo Importer', 'wpforge' ),
        'manage_options',
        'wpforge-demo-importer',
        'wpforge_demo_page_html'
    );
}
add_action( 'admin_menu', 'wpforge_register_demo_page' );

/**
 * 2. Render the Admin Page HTML (Professional UI)
 */
function wpforge_demo_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap wpforge-admin-wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'WPForge Demo Data Importer', 'wpforge' ); ?></h1>
        <hr class="wp-header-end">
        
        <div class="postbox" style="max-width: 800px; margin-top: 20px; border-radius: 6px; overflow: hidden;">
            <div class="postbox-header" style="background: #f6f7f7; border-bottom: 1px solid #c3c4c7; padding: 15px 20px;">
                <h2 style="margin: 0; font-size: 1.2em;"><?php esc_html_e( '1-Click Content Scaffolding', 'wpforge' ); ?></h2>
            </div>
            <div class="inside" style="padding: 20px; font-size: 1.1em; color: #3c434a;">
                <p><?php esc_html_e( 'Populate your theme with sample Projects, Services, and taxonomy terms. This allows you to instantly visualize the custom frontend templates without writing manual content.', 'wpforge' ); ?></p>
                
                <form method="post" action="" style="margin-top: 20px;">
                    <?php wp_nonce_field( 'wpforge_import_demo_action', 'wpforge_import_demo_nonce' ); ?>
                    <input type="hidden" name="wpforge_import_triggered" value="1">
                    <?php submit_button( __( 'Generate Demo Data', 'wpforge' ), 'primary large' ); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 3. Process the Form Submission and Insert Data
 */
function wpforge_process_demo_import() {
    if ( ! isset( $_POST['wpforge_import_triggered'] ) ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['wpforge_import_demo_nonce'] ) || ! wp_verify_nonce( $_POST['wpforge_import_demo_nonce'], 'wpforge_import_demo_action' ) ) {
        wp_die( esc_html__( 'Security check failed.', 'wpforge' ) );
    }

    // Taxonomies
    $taxonomies = array( 'React', 'Laravel', 'WordPress', 'WooCommerce' );
    $term_ids   = array();
    foreach ( $taxonomies as $tax ) {
        $term = term_exists( $tax, 'project_type' );
        if ( ! $term ) {
            $term = wp_insert_term( $tax, 'project_type' );
        }
        if ( ! is_wp_error( $term ) ) {
            $term_ids[$tax] = is_array( $term ) ? $term['term_id'] : $term;
        }
    }

    // Projects
    $dummy_projects = array(
        array(
            'title'   => 'Fintech Dashboard',
            'content' => 'A complex financial dashboard built with real-time data visualization. <!-- wp:paragraph --><p>This demonstrates advanced state management and secure API routing.</p><!-- /wp:paragraph -->',
            'client'  => 'Apex Financial',
            'tech'    => 'React, Redux, Node.js',
            'url'     => 'https://example.com/fintech',
            'terms'   => array( $term_ids['React'] )
        ),
        array(
            'title'   => 'Global E-Commerce Platform',
            'content' => 'High-converting multi-currency store with custom inventory management. <!-- wp:paragraph --><p>Features integrated shipping calculations and tax automation.</p><!-- /wp:paragraph -->',
            'client'  => 'RetailCore',
            'tech'    => 'WooCommerce, PHP 8',
            'url'     => 'https://example.com/shop',
            'terms'   => array( $term_ids['WooCommerce'], $term_ids['WordPress'] )
        ),
    );

    foreach ( $dummy_projects as $project ) {
        if ( ! post_exists( $project['title'] ) ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $project['title'],
                'post_content' => $project['content'],
                'post_status'  => 'publish',
                'post_type'    => 'project',
            ) );
            if ( $post_id && ! is_wp_error( $post_id ) ) {
                wp_set_object_terms( $post_id, $project['terms'], 'project_type' );
                update_post_meta( $post_id, '_wpforge_project_client', $project['client'] );
                update_post_meta( $post_id, '_wpforge_project_tech', $project['tech'] );
                update_post_meta( $post_id, '_wpforge_project_url', $project['url'] );
            }
        }
    }

    // Services
    $dummy_services = array( 'Custom Web Application Development', 'Headless E-Commerce Solutions' );
    foreach ( $dummy_services as $service_title ) {
        if ( ! post_exists( $service_title ) ) {
            wp_insert_post( array(
                'post_title'   => $service_title,
                'post_content' => '<!-- wp:paragraph --><p>We provide enterprise-grade solutions tailored to your specific business requirements, ensuring scalability and robust security.</p><!-- /wp:paragraph -->',
                'post_status'  => 'publish',
                'post_type'    => 'service',
            ) );
        }
    }

    // THE FIX: Redirect specifically to the new top-level admin.php URL
    wp_safe_redirect( admin_url( 'admin.php?page=wpforge-demo-importer&imported=true' ) );
    exit;
}
add_action( 'admin_init', 'wpforge_process_demo_import' );

/**
 * 4. Display Admin Notice on Success
 */
function wpforge_demo_import_notice() {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'wpforge-demo-importer' && isset( $_GET['imported'] ) && $_GET['imported'] === 'true' ) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Demo data successfully generated! Check your Projects and Services tabs.', 'wpforge' ) . '</strong></p></div>';
    }
}
add_action( 'admin_notices', 'wpforge_demo_import_notice' );

if ( ! function_exists( 'post_exists' ) ) {
    require_once ABSPATH . 'wp-admin/includes/post.php';
}