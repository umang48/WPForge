<?php
/**
 * Template part for displaying posts
 *
 * @package WPForge
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 50px; border-bottom: 1px solid #eee; padding-bottom: 30px;">
    
    <header class="entry-header" style="margin-bottom: 20px;">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title" style="font-size: 2.5em; margin-bottom: 10px;">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title" style="font-size: 2em; margin-bottom: 10px;"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" style="text-decoration: none; color: #333;">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta" style="color: #666; font-size: 0.9em;">
                <?php
                echo '<span class="posted-on">' . esc_html( get_the_date() ) . '</span> | ';
                echo '<span class="byline">' . esc_html__( 'By ', 'wpforge' ) . esc_html( get_the_author() ) . '</span>';
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if ( has_post_thumbnail() && ! is_singular() ) : ?>
        <div class="post-thumbnail" style="margin-bottom: 20px;">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: auto; border-radius: 4px;' ) ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content" style="line-height: 1.8;">
        <?php
        if ( is_singular() ) {
            the_content();
        } else {
            the_excerpt();
            echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more" style="display: inline-block; margin-top: 15px; font-weight: bold; color: #0073aa; text-decoration: none;">' . esc_html__( 'Continue reading &rarr;', 'wpforge' ) . '</a>';
        }

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpforge' ),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->