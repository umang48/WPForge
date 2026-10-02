<?php
/**
 * Template part for displaying project items in the archive grid.
 *
 * @package WPForge
 */

// 1. Fetch the custom taxonomy terms (e.g., React, Laravel) for this specific post
$project_types = get_the_terms( get_the_ID(), 'project_type' );
$term_classes  = '';
$term_names    = array();

if ( $project_types && ! is_wp_error( $project_types ) ) {
    foreach ( $project_types as $term ) {
        // We add these to the article class for CSS/JS targeting later
        $term_classes .= ' type-' . esc_attr( $term->slug );
        $term_names[]  = esc_html( $term->name );
    }
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'project-card' . $term_classes ); ?> style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
    
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="project-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php 
                // Display the custom image size we registered in inc/setup.php
                the_post_thumbnail( 'wpforge-project-grid', array( 'style' => 'width: 100%; height: auto; display: block;' ) ); 
                ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="project-content" style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
        <header class="entry-header">
            <?php
            // Output the taxonomy terms
            if ( ! empty( $term_names ) ) {
                echo '<span class="project-category" style="font-size: 0.8em; text-transform: uppercase; color: #666; font-weight: bold;">' . join( ', ', $term_names ) . '</span>';
            }
            
            // Output the Title
            the_title( '<h2 class="entry-title" style="margin: 10px 0 15px; font-size: 1.5em;"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" style="text-decoration: none; color: #333;">', '</a></h2>' );
            ?>
        </header>

        <div class="entry-summary" style="margin-bottom: 20px; color: #555; line-height: 1.6;">
            <?php the_excerpt(); ?>
        </div>
        
        <footer class="entry-footer" style="margin-top: auto;">
            <a href="<?php the_permalink(); ?>" class="button project-read-more" style="display: inline-block; padding: 10px 20px; background: #0073aa; color: #fff; text-decoration: none; border-radius: 4px;">
                <?php esc_html_e( 'View Details &rarr;', 'wpforge' ); ?>
            </a>
        </footer>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->