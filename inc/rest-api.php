<?php
/**
 * Custom REST API Endpoints for WPForge
 *
 * @package WPForge
 */

/**
 * Register custom REST API routes.
 */
function wpforge_register_rest_routes() {
    register_rest_route( 'wpforge/v1', '/latest-projects', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'wpforge_get_latest_projects_endpoint',
        'permission_callback' => '__return_true', // Publicly accessible read endpoint
    ) );
}
add_action( 'rest_api_init', 'wpforge_register_rest_routes' );

/**
 * Callback for the latest projects endpoint.
 *
 * @param WP_REST_Request $request The incoming API request.
 * @return WP_REST_Response|WP_Error
 */
function wpforge_get_latest_projects_endpoint( $request ) {
    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $projects_query = new WP_Query( $args );
    $data           = array();

    if ( $projects_query->have_posts() ) {
        while ( $projects_query->have_posts() ) {
            $projects_query->the_post();
            
            // Extract taxonomy terms
            $terms = get_the_terms( get_the_ID(), 'project_type' );
            $term_names = array();
            if ( $terms && ! is_wp_error( $terms ) ) {
                $term_names = wp_list_pluck( $terms, 'name' );
            }

            // Build the clean JSON schema
            $data[] = array(
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'link'      => get_permalink(),
                'excerpt'   => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url( get_the_ID(), 'wpforge-project-grid' ),
                'client'    => get_post_meta( get_the_ID(), '_wpforge_project_client', true ),
                'tech'      => get_post_meta( get_the_ID(), '_wpforge_project_tech', true ),
                'terms'     => $term_names,
            );
        }
        wp_reset_postdata();
    }

    // Wrap the array in a proper REST response object
    return rest_ensure_response( $data );
}


// 1. Register Team Members CPT
    $team_labels = array(
        'name'                  => _x( 'Team Members', 'Post Type General Name', 'wpforge' ),
        'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'wpforge' ),
        'menu_name'             => __( 'Team', 'wpforge' ),
        'all_items'             => __( 'All Team Members', 'wpforge' ),
        'add_new_item'          => __( 'Add New Team Member', 'wpforge' ),
        'edit_item'             => __( 'Edit Team Member', 'wpforge' ),
    );
    
    $team_args = array(
        'label'                 => __( 'Team Member', 'wpforge' ),
        'labels'                => $team_labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 22,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true, // Enables Gutenberg editor
    );
    register_post_type( 'team', $team_args );

    // 2. Register Testimonials CPT
    $testimonial_labels = array(
        'name'                  => _x( 'Testimonials', 'Post Type General Name', 'wpforge' ),
        'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'wpforge' ),
        'menu_name'             => __( 'Testimonials', 'wpforge' ),
        'all_items'             => __( 'All Testimonials', 'wpforge' ),
        'add_new_item'          => __( 'Add New Testimonial', 'wpforge' ),
        'edit_item'             => __( 'Edit Testimonial', 'wpforge' ),
    );
    
    $testimonial_args = array(
        'label'                 => __( 'Testimonial', 'wpforge' ),
        'labels'                => $testimonial_labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'hierarchical'          => false,
        'public'                => false, // Testimonials usually don't need their own single URL
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 23,
        'menu_icon'             => 'dashicons-testimonial',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false, // Keeps them out of sitemaps as standalone pages
        'show_in_rest'          => true,
    );
    register_post_type( 'testimonial', $testimonial_args );