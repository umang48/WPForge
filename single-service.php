<?php
/**
 * The template for displaying all single services.
 *
 * @package WPForge
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- 1. Service Hero Section -->
            <header class="service-header" style="background: var(--wp--preset--color--surface, #f9f9f9); padding: 80px 0; text-align: center; border-bottom: 1px solid #eee;">
                <div class="container">
                    <?php the_title( '<h1 class="entry-title" style="font-size: 3em; margin-bottom: 20px; color: var(--wp--preset--color--primary, #0073aa);">', '</h1>' ); ?>
                    
                    <?php if ( has_excerpt() ) : ?>
                        <div class="service-excerpt" style="font-size: 1.2em; color: var(--wp--preset--color--text-muted, #666); max-width: 800px; margin: 0 auto;">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="container" style="padding: 60px 20px;">
                <div class="service-layout" style="display: flex; flex-wrap: wrap; gap: 50px;">
                    
                    <!-- 2. Main Content (Gutenberg Blocks) -->
                    <div class="service-content" style="flex: 1; min-width: 300px; line-height: 1.8;">
                        <?php 
                        if ( has_post_thumbnail() ) {
                            echo '<div class="service-thumbnail" style="margin-bottom: 40px;">';
                            the_post_thumbnail( 'full', array( 'style' => 'width: 100%; height: auto; border-radius: 8px;' ) );
                            echo '</div>';
                        }
                        
                        // This is where the agency explains the service using custom blocks and patterns
                        the_content(); 
                        ?>
                    </div>

                    <!-- 3. Conversion Sidebar -->
                    <div class="service-sidebar" style="width: 100%; max-width: 350px; flex-shrink: 0;">
                        <div class="service-cta-box" style="background: var(--wp--preset--color--primary, #0073aa); color: #fff; padding: 40px; border-radius: 8px; text-align: center; position: sticky; top: 40px;">
                            
                            <h3 style="margin-top: 0; color: #fff; font-size: 1.8em;">
                                <?php esc_html_e( 'Need this service?', 'wpforge' ); ?>
                            </h3>
                            
                            <p style="margin-bottom: 30px; line-height: 1.6; font-size: 1.1em;">
                                <?php esc_html_e( 'Our team of experts is ready to help you build your next big project. Let\'s discuss your requirements.', 'wpforge' ); ?>
                            </p>
                            
                            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button" style="background: #fff; color: var(--wp--preset--color--primary, #0073aa); width: 100%; text-align: center; box-sizing: border-box; padding: 15px; font-size: 1.1em;">
                                <?php esc_html_e( 'Contact Us Today', 'wpforge' ); ?>
                            </a>
                            
                        </div>
                    </div>

                </div><!-- .service-layout -->
            </div><!-- .container -->

        </article><!-- #post-<?php the_ID(); ?> -->

        <?php
    endwhile; // End of the loop.
    ?>
</main><!-- #primary -->

<?php
get_footer();