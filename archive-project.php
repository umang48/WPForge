<?php
/**
 * The template for displaying project archives.
 *
 * @package WPForge
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <?php
            the_archive_title( '<h1 class="page-title">', '</h1>' );
            the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
            
            <!-- AJAX Filter Navigation -->
            <div class="project-filters" id="project-filters" style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 40px; justify-content: center;">
                <button class="filter-btn active" data-filter="all" style="padding: 8px 20px; border: 2px solid #0073aa; background: #0073aa; color: #fff; border-radius: 20px; cursor: pointer; font-weight: bold;">
                    <?php esc_html_e( 'All', 'wpforge' ); ?>
                </button>
                
                <?php
                // Dynamically fetch all Project Type terms
                $terms = get_terms( array(
                    'taxonomy'   => 'project_type',
                    'hide_empty' => true, // Only show terms that actually have projects
                ) );

                if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        printf(
                            '<button class="filter-btn" data-filter="%s" style="padding: 8px 20px; border: 2px solid #0073aa; background: transparent; color: #0073aa; border-radius: 20px; cursor: pointer; font-weight: bold;">%s</button>',
                            esc_attr( $term->slug ),
                            esc_html( $term->name )
                        );
                    }
                }
                ?>
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            
            <div class="project-grid" id="project-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-top: 40px;">
                <?php
                // Start the Loop.
                while ( have_posts() ) :
                    the_post();
                    
                    // Load the template part for the project card.
                    get_template_part( 'template-parts/content', 'project' );
                
                endwhile;
                ?>
            </div><!-- .project-grid -->

            <?php
            // Native WordPress pagination
            the_posts_navigation( array(
                'prev_text' => esc_html__( 'Older Projects', 'wpforge' ),
                'next_text' => esc_html__( 'Newer Projects', 'wpforge' ),
            ) );
            ?>

        <?php else : ?>
            
            <p><?php esc_html_e( 'No projects found. Check back soon!', 'wpforge' ); ?></p>
            
        <?php endif; ?>
    </div>
</main><!-- #primary -->

<?php
get_footer();