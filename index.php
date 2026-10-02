<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package WPForge
 */

get_header();
?>

<main id="primary" class="site-main" style="padding: 60px 0;">
    <div class="container" style="display: flex; gap: 40px; flex-wrap: wrap;">
        
        <!-- Main Blog Content Area -->
        <div class="main-content" style="flex: 2; min-width: 300px;">
            <?php
            if ( have_posts() ) :

                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'template-parts/content', get_post_type() );

                endwhile;

                the_posts_navigation(
                    array(
                        'prev_text' => esc_html__( 'Older posts', 'wpforge' ),
                        'next_text' => esc_html__( 'Newer posts', 'wpforge' ),
                    )
                );

            else :

                // Fallback if no posts exist
                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>
        </div><!-- .main-content -->

        <!-- Blog Sidebar Area -->
        <div class="sidebar-area" style="flex: 1; min-width: 250px;">
            <?php get_sidebar(); ?>
        </div>

    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();