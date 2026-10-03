<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WPForge
 */

get_header();
?>

<main id="primary" class="site-main" style="padding: 100px 0; text-align: center;">
    <div class="container" style="max-width: 600px; margin: 0 auto;">

        <section class="error-404 not-found">
            <header class="page-header" style="margin-bottom: 30px;">
                <h1 class="page-title" style="font-size: 4em; margin-bottom: 10px; color: var(--wp--preset--color--primary, #0073aa);">
                    <?php esc_html_e( '404', 'wpforge' ); ?>
                </h1>
                <h2 style="font-size: 2em; margin-top: 0;">
                    <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'wpforge' ); ?>
                </h2>
            </header><!-- .page-header -->

            <div class="page-content" style="line-height: 1.8;">
                <p style="margin-bottom: 30px; font-size: 1.2em; color: var(--wp--preset--color--text-muted, #666);">
                    <?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search or check out our recent projects?', 'wpforge' ); ?>
                </p>

                <div class="search-form-wrapper" style="margin-bottom: 40px;">
                    <?php get_search_form(); ?>
                </div>

                <div class="quick-links" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button" style="padding: 12px 25px;">
                        <?php esc_html_e( 'Return Home', 'wpforge' ); ?>
                    </a>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" class="button button-outline" style="padding: 12px 25px;">
                        <?php esc_html_e( 'View Projects', 'wpforge' ); ?>
                    </a>
                </div>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->

    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();