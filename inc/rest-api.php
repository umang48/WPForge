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