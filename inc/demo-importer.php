<?php
/**
 * Programmatic Demo Data Importer
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;


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
/**
 * 3. Process the Form Submission (Upgraded for Pages & Menus)
 */
function wpforge_process_demo_import() {
    if ( ! isset( $_POST['wpforge_import_triggered'] ) ) return;
    if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['wpforge_import_demo_nonce'] ) || ! wp_verify_nonce( $_POST['wpforge_import_demo_nonce'], 'wpforge_import_demo_action' ) ) {
        wp_die( esc_html__( 'Security check failed.', 'wpforge' ) );
    }

    // A. Generate Core Pages (Check for duplicates first)
    $pages = array(
        'Home'     => 'Welcome to WPForge Multipurpose Theme.',
        'Blog'     => 'Our latest news and insights.',
        'Services' => 'What we can do for you.',
        'Contact'  => '<!-- wp:pattern {"slug":"wpforge/cta-section"} /-->'
    );

    $page_ids = array();
    foreach ( $pages as $title => $content ) {
        $existing_page = get_page_by_title( $title );
        if ( ! $existing_page ) {
            $page_ids[$title] = wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
        } else {
            $page_ids[$title] = $existing_page->ID;
        }
    }

    // B. Set Front Page and Posts Page
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $page_ids['Home'] );
    update_option( 'page_for_posts', $page_ids['Blog'] );

    // C. Generate and Assign the Navigation Menu
    $menu_name = 'WPForge Main Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    
    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        
        // Add items to menu
        foreach ( $page_ids as $title => $id ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'     => $title,
                'menu-item-object-id' => $id,
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish'
            ) );
        }
        
        // Assign to 'primary' theme location
        $locations = get_theme_mod( 'nav_menu_locations', array() );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    // D. Generate Dummy Projects (Duplicate check included)
    if ( ! function_exists( 'post_exists' ) ) require_once ABSPATH . 'wp-admin/includes/post.php';
    if ( ! post_exists( 'Fintech Dashboard' ) ) {
        wp_insert_post( array( 'post_title' => 'Fintech Dashboard', 'post_content' => 'Sample content', 'post_status' => 'publish', 'post_type' => 'project' ) );
    }


    // ... existing code for Pages, Menus, and Projects ...

    // E. Generate Dummy Team Members
    // E. Generate Dummy Team Members
    $dummy_team = array(
        array(
            'name'     => 'Jane Doe',
            'bio'      => '<!-- wp:paragraph --><p>Lead Full-Stack Developer with over 10 years of experience specializing in React, Laravel, and enterprise WordPress architectures.</p><!-- /wp:paragraph -->',
            'title'    => 'Lead Full-Stack Developer',
            'linkedin' => 'https://linkedin.com/',
            'github'   => 'https://github.com/',
            'order'    => 1
        ),
        array(
            'name'     => 'John Smith',
            'bio'      => '<!-- wp:paragraph --><p>Senior UI/UX Designer focused on creating WCAG-compliant, high-performance design systems for modern digital agencies.</p><!-- /wp:paragraph -->',
            'title'    => 'Senior UI/UX Designer',
            'linkedin' => 'https://linkedin.com/',
            'github'   => '',
            'order'    => 2
        )
    );

    foreach ( $dummy_team as $member ) {
        if ( ! post_exists( $member['name'], '', '', 'team' ) ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $member['name'],
                'post_content' => $member['bio'],
                'post_status'  => 'publish',
                'post_type'    => 'team',
                'menu_order'   => $member['order'],
            ) );

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_wpforge_team_job_title', $member['title'] );
                update_post_meta( $post_id, '_wpforge_team_linkedin', $member['linkedin'] );
                update_post_meta( $post_id, '_wpforge_team_github', $member['github'] );
            }
        }
    }

    // F. Generate Dummy Testimonials
    $dummy_testimonials = array(
        array(
            'author' => 'Sarah Jenkins, CEO at TechCorp',
            'quote'  => '<!-- wp:paragraph --><p>"WPForge transformed our online presence. Their attention to performance, security hardening, and structural detail is unmatched in the industry."</p><!-- /wp:paragraph -->',
            'order'  => 1
        ),
        array(
            'author' => 'Michael Chen, Founder of StartUp Inc.',
            'quote'  => '<!-- wp:paragraph --><p>"The custom REST API integration allowed us to scale our React mobile app seamlessly. The backend is remarkably clean and incredibly fast. Highly recommended!"</p><!-- /wp:paragraph -->',
            'order'  => 2
        ),
        array(
            'author' => 'Elena Rodriguez, CTO at Nexus Solutions',
            'quote'  => '<!-- wp:paragraph --><p>"Finally, a theme that respects proper data architecture. Bypassing heavy page builders for native block patterns improved our Core Web Vitals instantly."</p><!-- /wp:paragraph -->',
            'order'  => 3
        )
    );

    foreach ( $dummy_testimonials as $testimonial ) {
        if ( ! post_exists( $testimonial['author'], '', '', 'testimonial' ) ) {
            wp_insert_post( array(
                'post_title'   => $testimonial['author'],
                'post_content' => $testimonial['quote'],
                'post_status'  => 'publish',
                'post_type'    => 'testimonial',
                'menu_order'   => $testimonial['order'],
            ) );
        }
    }

   

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