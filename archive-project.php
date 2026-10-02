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
            
            <!-- AJAX Filter Navigation (Placeholder for Phase 4) -->
            <div class="project-filters" id="project-filters">
                <!-- Filter buttons will be injected here -->
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