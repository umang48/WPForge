<?php
/**
 * The header for our theme
 *
 * @package WPForge
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); // CRITICAL: Allows plugins and our enqueue.php to load assets ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); // Required since WP 5.2 for injecting scripts right after body tag ?>

<div id="page" class="site">
    
    <!-- Accessibility: Skip to content link -->
    <a class="skip-link screen-reader-text" href="#primary" style="position: absolute; left: -9999px; z-index: 999;">
        <?php esc_html_e( 'Skip to content', 'wpforge' ); ?>
    </a>

    <header id="masthead" class="site-header" style="padding: 20px 0; border-bottom: 1px solid #eee; margin-bottom: 40px;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            
            <!-- Site Branding -->
            <div class="site-branding">
                <?php
                $options = get_option( 'wpforge_theme_options' );
                if ( ! empty( $options['logo'] ) ) : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img src="<?php echo esc_url( $options['logo'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" style="max-height: 50px;">
                    </a>
                <?php else : ?>
                    <h1 class="site-title" style="margin: 0; font-size: 1.8em;">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; color: var(--wp--preset--color--primary);"><?php bloginfo( 'name' ); ?></a>
                    </h1>
                <?php endif; ?>
            </div><!-- .site-branding -->

            <!-- Primary Navigation -->
            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'wpforge' ); ?>">
                
                <!-- Accessible Mobile Menu Toggle -->
                <button id="menu-toggle" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'wpforge' ); ?></span>
                    <span class="hamburger-icon" aria-hidden="true"></span>
                </button>

                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false, // Don't fall back to wp_page_menu
                        'items_wrap'     => '<ul id="%1$s" class="%2$s" style="list-style: none; margin: 0; padding: 0;">%3$s</ul>',
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
            
        </div><!-- .container -->
    </header><!-- #masthead -->