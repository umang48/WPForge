<?php
/**
 * The template for displaying all single projects.
 *
 * @package WPForge
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

    <?php 
        // Render SEO Breadcrumbs
        if ( function_exists( 'wpforge_breadcrumbs' ) ) {
            wpforge_breadcrumbs();
        } 
        ?>
        
        <?php
        while ( have_posts() ) :
            the_post();

            // 1. Securely retrieve custom meta fields
            $client = get_post_meta( get_the_ID(), '_wpforge_project_client', true );
            $tech   = get_post_meta( get_the_ID(), '_wpforge_project_tech', true );
            $url    = get_post_meta( get_the_ID(), '_wpforge_project_url', true );
            
            // 2. Retrieve custom taxonomy terms
            $project_types = get_the_terms( get_the_ID(), 'project_type' );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header" style="margin-bottom: 30px; text-align: center;">
                    <?php the_title( '<h1 class="entry-title" style="font-size: 2.5em; margin-bottom: 10px;">', '</h1>' ); ?>
                    
                    <?php if ( $project_types && ! is_wp_error( $project_types ) ) : ?>
                        <div class="project-terms" style="color: #666; font-weight: bold; text-transform: uppercase; font-size: 0.9em;">
                            <?php 
                            // Extract just the names into an array and join with commas
                            $type_names = wp_list_pluck( $project_types, 'name' );
                            echo esc_html( join( ' | ', $type_names ) );
                            ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="project-hero-image" style="margin-bottom: 40px;">
                        <?php the_post_thumbnail( 'full', array( 'style' => 'width: 100%; height: auto; border-radius: 8px;' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="project-layout" style="display: flex; gap: 50px; flex-wrap: wrap;">
                    
                    <!-- Main Content Area -->
                    <div class="project-content" style="flex: 2; min-width: 300px; line-height: 1.8;">
                        <?php
                        the_content();

                        // Handles pagination if a single post is broken into multiple pages using <!-- nextpage -->
                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpforge' ),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div><!-- .project-content -->

                    <!-- Project Meta Sidebar -->
                    <div class="project-meta-sidebar" style="flex: 1; min-width: 250px; background: #f9f9f9; padding: 30px; border-radius: 8px; align-self: flex-start; border: 1px solid #eee;">
                        <h3 style="margin-top: 0; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                            <?php esc_html_e( 'Project Details', 'wpforge' ); ?>
                        </h3>
                        
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            
                            <?php if ( ! empty( $client ) ) : ?>
                                <li style="margin-bottom: 15px;">
                                    <strong style="display: block; color: #333; font-size: 0.9em; text-transform: uppercase;"><?php esc_html_e( 'Client', 'wpforge' ); ?></strong>
                                    <?php echo esc_html( $client ); ?>
                                </li>
                            <?php endif; ?>

                            <?php if ( ! empty( $tech ) ) : ?>
                                <li style="margin-bottom: 15px;">
                                    <strong style="display: block; color: #333; font-size: 0.9em; text-transform: uppercase;"><?php esc_html_e( 'Technology', 'wpforge' ); ?></strong>
                                    <?php echo esc_html( $tech ); ?>
                                </li>
                            <?php endif; ?>

                            <?php if ( ! empty( $url ) ) : ?>
                                <li style="margin-top: 30px;">
                                    <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="button" style="display: block; text-align: center; background: #0073aa; color: #fff; padding: 12px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                                        <?php esc_html_e( 'View Live Project &rarr;', 'wpforge' ); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                        </ul>
                    </div><!-- .project-meta-sidebar -->

                </div><!-- .project-layout -->

            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Native post navigation (Next/Previous Project)
            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous Project', 'wpforge' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next Project', 'wpforge' ) . '</span> <span class="nav-title">%title</span>',
                )
            );

        endwhile; // End of the loop.
        ?>

    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();