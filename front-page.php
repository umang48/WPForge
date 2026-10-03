<?php
/**
 * The front page template file
 *
 * @package WPForge
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- 1. HERO SECTION (Pulls from the page's Gutenberg content) -->
    <section class="hero-section" style="background: #0073aa; color: #fff; padding: 100px 0; text-align: center;">
        <div class="container">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    the_title( '<h1 class="hero-title" style="font-size: 3em; margin-bottom: 20px;">', '</h1>' );
                    
                    echo '<div class="hero-content" style="font-size: 1.2em; max-width: 800px; margin: 0 auto 30px;">';
                    the_content();
                    echo '</div>';
                endwhile;
            endif;
            ?>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="button button-primary" style="background: #fff; color: #0073aa; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 1.1em;">
                <?php esc_html_e( 'Work With Us', 'wpforge' ); ?>
            </a>
        </div>
    </section>

    <!-- 2. SERVICES SECTION (Custom WP_Query) -->
    <section class="services-section" style="padding: 80px 0; background: #f9f9f9;">
        <div class="container">
            <header class="section-header" style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.2em; margin-bottom: 10px;"><?php esc_html_e( 'Our Expertise', 'wpforge' ); ?></h2>
                <p style="color: #666; font-size: 1.1em;"><?php esc_html_e( 'Specialized web development solutions for modern businesses.', 'wpforge' ); ?></p>
            </header>

            <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <?php
                $services_args = array(
                    'post_type'      => 'service',
                    'posts_per_page' => 3,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                );
                $services_query = new WP_Query( $services_args );

                if ( $services_query->have_posts() ) :
                    while ( $services_query->have_posts() ) :
                        $services_query->the_post();
                        ?>
                        <div class="service-card" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div style="margin-bottom: 20px;">
                                    <?php the_post_thumbnail( 'thumbnail', array( 'style' => 'width: 64px; height: 64px; border-radius: 50%;' ) ); ?>
                                </div>
                            <?php endif; ?>
                            <h3 style="margin-top: 0;"><a href="<?php the_permalink(); ?>" style="text-decoration: none; color: #333;"><?php the_title(); ?></a></h3>
                            <div style="color: #666; margin-bottom: 20px;">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata(); // CRITICAL: Restore original post data
                else :
                    echo '<p>' . esc_html__( 'No services found.', 'wpforge' ) . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- 3. RECENT PROJECTS SECTION (Custom WP_Query reusing our template part) -->
    <section class="recent-projects-section" style="padding: 80px 0;">
        <div class="container">
            <header class="section-header" style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.2em; margin-bottom: 10px;"><?php esc_html_e( 'Recent Work', 'wpforge' ); ?></h2>
                <p style="color: #666; font-size: 1.1em;"><?php esc_html_e( 'A selection of our latest development projects.', 'wpforge' ); ?></p>
            </header>

            <div class="project-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <?php
                $projects_args = array(
                    'post_type'      => 'project',
                    'posts_per_page' => 3,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                $projects_query = new WP_Query( $projects_args );

                if ( $projects_query->have_posts() ) :
                    while ( $projects_query->have_posts() ) :
                        $projects_query->the_post();
                        
                        // We reuse the exact same template part we built for the archive!
                        get_template_part( 'template-parts/content', 'project' );
                    
                    endwhile;
                    wp_reset_postdata(); // CRITICAL: Restore original post data
                endif;
                ?>
            </div>

            <div style="text-align: center; margin-top: 50px;">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" class="button button-outline" style="border: 2px solid #0073aa; color: #0073aa; padding: 12px 25px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    <?php esc_html_e( 'View All Projects &rarr;', 'wpforge' ); ?>
                </a>
            </div>
        </div>
    </section>


    <!-- 4. DYNAMIC REST API SECTION -->
    <section class="api-projects-section" style="padding: 80px 0; background: #f0f7fa;">
        <div class="container">
            <header class="section-header" style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.2em; margin-bottom: 10px;"><?php esc_html_e( 'API-Powered Feed', 'wpforge' ); ?></h2>
                <p style="color: #666; font-size: 1.1em;"><?php esc_html_e( 'These projects are fetched asynchronously via our custom REST API endpoint.', 'wpforge' ); ?></p>
            </header>

            <!-- The mount point for our JavaScript -->
            <div id="rest-api-projects-grid" class="project-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <p style="text-align: center; width: 100%; color: #666; font-style: italic;">
                    <?php esc_html_e( 'Loading latest projects...', 'wpforge' ); ?>
                </p>
            </div>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();