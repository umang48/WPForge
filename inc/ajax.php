<?php
/**
 * AJAX Functions for WPForge
 *
 * @package WPForge
 */

function wpforge_filter_projects_callback() {
    // 1. Verify the AJAX nonce for security
    check_ajax_referer( 'wpforge_ajax_nonce', 'nonce' );

    // 2. Sanitize the incoming filter parameter
    $term_slug = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : 'all';

    // 3. Build the query arguments
    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => -1, // For a real portfolio, you might limit this and add a 'Load More' button
        'post_status'    => 'publish',
    );

    // 4. Add taxonomy query if a specific term was selected
    if ( 'all' !== $term_slug ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => $term_slug,
            ),
        );
    }

    $projects_query = new WP_Query( $args );

    // 5. Start output buffering to capture the HTML
    ob_start();

    if ( $projects_query->have_posts() ) :
        while ( $projects_query->have_posts() ) :
            $projects_query->the_post();
            
            // Reuse our existing template part!
            get_template_part( 'template-parts/content', 'project' );
        
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>' . esc_html__( 'No projects found for this category.', 'wpforge' ) . '</p>';
    endif;

    // 6. Clean the buffer and store it in a variable
    $response_html = ob_get_clean();

    // 7. Send the JSON response back to JavaScript
    wp_send_json_success( $response_html );
}
// Register the endpoint for both logged-in and guest users
add_action( 'wp_ajax_wpforge_filter_projects', 'wpforge_filter_projects_callback' );
add_action( 'wp_ajax_nopriv_wpforge_filter_projects', 'wpforge_filter_projects_callback' );