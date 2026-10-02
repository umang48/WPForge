<?php
/**
 * The template for displaying the footer
 *
 * @package WPForge
 */
?>

    <footer id="colophon" class="site-footer" style="background: #f9f9f9; padding: 40px 0 20px; margin-top: 60px; border-top: 1px solid #eee;">
        <div class="container">
            
            <div class="footer-widgets" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div class="footer-info">
                    <h2 class="site-title-footer" style="margin-top: 0;">
                        <?php bloginfo( 'name' ); ?>
                    </h2>
                    <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
                </div>
                
                <nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer menu', 'wpforge' ); ?>">
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'depth'          => 1,
                                'container'      => false,
                                'items_wrap'     => '<ul id="%1$s" class="%2$s" style="list-style: none; padding: 0; margin: 0;">%3$s</ul>',
                            )
                        );
                    }
                    ?>
                </nav>
            </div>

            <div class="site-info" style="text-align: center; border-top: 1px solid #ddd; padding-top: 20px; font-size: 14px; color: #666;">
                <p>
                    &copy; <?php echo esc_html( date_i18n( __( 'Y', 'wpforge' ) ) ); ?> 
                    <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. 
                    <?php esc_html_e( 'All rights reserved.', 'wpforge' ); ?>
                </p>
            </div><!-- .site-info -->
            
        </div><!-- .container -->
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); // CRITICAL: Loads bottom-of-page scripts and admin bar ?>

</body>
</html>