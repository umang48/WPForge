<?php
/**
 * Programmatic Demo Data Importer
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Add the menu page under Appearance
 */
function wpforge_register_demo_page() {
    add_submenu_page(
        'wpforge-options',                   // Parent slug (Connects to our new menu)
        __( 'WPForge Demo Data', 'wpforge' ),
        __( 'Import Demo Data', 'wpforge' ),
        'manage_options',
        'wpforge-demo-importer',
        'wpforge_demo_page_html'
    );
}
add_action( 'admin_menu', 'wpforge_register_demo_page' );

/**
 * 2. Render the Admin Page HTML
 */
function wpforge_demo_page_html() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'WPForge Demo Content Importer', 'wpforge' ); ?></h1>
        <p><?php esc_html_e( 'Click the button below to generate sample Projects, Services, and taxonomy terms. This will populate your theme so you can see the layouts in action.', 'wpforge' ); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field( 'wpforge_import_demo_action', 'wpforge_import_demo_nonce' ); ?>
            <input type="hidden" name="wpforge_import_triggered" value="1">
            <?php submit_button( __( 'Generate Demo Data', 'wpforge' ), 'primary' ); ?>
        </form>
    </div>
    <?php
}

/**
 * 3. Process the Form Submission and Insert Data
 */
function wpforge_process_demo_import() {
    // Check if form was submitted
    if ( ! isset( $_POST['wpforge_import_triggered'] ) ) {
        return;
    }

    // Security checks
    if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['wpforge_import_demo_nonce'] ) || ! wp_verify_nonce( $_POST['wpforge_import_demo_nonce'], 'wpforge_import_demo_action' ) ) {
        wp_die( esc_html__( 'Security check failed.', 'wpforge' ) );
    }

    // 4. Create dummy Project Types (Taxonomies)
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

    // 5. Create dummy Projects
    $dummy_projects = array(
        array(
            'title'   => 'Fintech Dashboard',
            'content' => 'A complex financial dashboard built with real-time data visualization. ' . '<!-- wp:paragraph --><p>This demonstrates advanced state management and secure API routing.</p><!-- /wp:paragraph -->',
            'client'  => 'Apex Financial',
            'tech'    => 'React, Redux, Node.js',
            'url'     => 'https://example.com/fintech',
            'terms'   => array( $term_ids['React'] )
        ),
        array(
            'title'   => 'Global E-Commerce Platform',
            'content' => 'High-converting multi-currency store with custom inventory management. ' . '<!-- wp:paragraph --><p>Features integrated shipping calculations and tax automation.</p><!-- /wp:paragraph -->',
            'client'  => 'RetailCore',
            'tech'    => 'WooCommerce, PHP 8',
            'url'     => 'https://example.com/shop',
            'terms'   => array( $term_ids['WooCommerce'], $term_ids['WordPress'] )
        ),
        array(
            'title'   => 'SaaS CRM Application',
            'content' => 'A headless CRM solution delivering sub-second load times via edge caching. ' . '<!-- wp:paragraph --><p>Built utilizing a decoupled architecture.</p><!-- /wp:paragraph -->',
            'client'  => 'Nexus Cloud',
            'tech'    => 'Laravel, Vue.js',
            'url'     => 'https://example.com/crm',
            'terms'   => array( $term_ids['Laravel'] )
        ),
    );

    foreach ( $dummy_projects as $project ) {
        // Prevent duplicate injection if user clicks button multiple times
        if ( ! post_exists( $project['title'] ) ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $project['title'],
                'post_content' => $project['content'],
                'post_status'  => 'publish',
                'post_type'    => 'project',
            ) );

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                // Assign taxonomies
                wp_set_object_terms( $post_id, $project['terms'], 'project_type' );
                
                // Assign custom meta boxes
                update_post_meta( $post_id, '_wpforge_project_client', $project['client'] );
                update_post_meta( $post_id, '_wpforge_project_tech', $project['tech'] );
                update_post_meta( $post_id, '_wpforge_project_url', $project['url'] );
            }
        }
    }

    // 6. Create dummy Services
    $dummy_services = array(
        'Custom Web Application Development',
        'Headless E-Commerce Solutions',
        'Legacy System Migration & Refactoring'
    );

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

    // Redirect to prevent form resubmission and show success message
    wp_safe_redirect( admin_url( 'themes.php?page=wpforge-demo-importer&imported=true' ) );
    exit;
}
add_action( 'admin_init', 'wpforge_process_demo_import' );

/**
 * 7. Display Admin Notice on Success
 */
function wpforge_demo_import_notice() {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'wpforge-demo-importer' && isset( $_GET['imported'] ) && $_GET['imported'] === 'true' ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong><?php esc_html_e( 'Demo data successfully generated! Check your Projects and Services tabs.', 'wpforge' ); ?></strong></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'wpforge_demo_import_notice' );

// Helper function to check if post exists to prevent duplicates
if ( ! function_exists( 'post_exists' ) ) {
    require_once ABSPATH . 'wp-admin/includes/post.php';
}