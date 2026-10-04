<?php
/**
 * The template for displaying all single pages
 *
 * @package WPForge
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="padding: 60px 20px;">
        <?php 
        // Render SEO Breadcrumbs
        if ( function_exists( 'wpforge_breadcrumbs' ) ) {
            wpforge_breadcrumbs();
        } 
        ?>
        
        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header" style="margin-bottom: 40px; text-align: center;">
                    <?php the_title( '<h1 class="entry-title" style="font-size: 2.5em; color: var(--wp--preset--color--primary, #0073aa);">', '</h1>' ); ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="page-thumbnail" style="margin-bottom: 40px; text-align: center;">
                        <?php the_post_thumbnail( 'full', array( 'style' => 'max-width: 100%; height: auto; border-radius: 8px;' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content" style="line-height: 1.8; max-width: 800px; margin: 0 auto;">
                    <?php
                    the_content();

                    // Handles pagination if a page uses the "Page Break" block
                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">' . esc_html__( 'Pages:', 'wpforge' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div><!-- .entry-content -->

            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();