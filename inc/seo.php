<?php
/**
 * SEO enhancements and plugin compatibility.
 *
 * @package WPForge
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render SEO Breadcrumbs (Supports Rank Math and Yoast).
 */
function wpforge_breadcrumbs() {
    $breadcrumb_html = '';

    // 1. Check for Rank Math
    if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
        ob_start();
        rank_math_the_breadcrumbs();
        $breadcrumb_html = ob_get_clean();
    } 
    // 2. Check for Yoast
    elseif ( function_exists( 'yoast_breadcrumb' ) ) {
        // The third parameter 'false' tells Yoast to return the string instead of echoing it
        $breadcrumb_html = yoast_breadcrumb( '', '', false );
    }

    // 3. Render if a plugin is active and returned data
    if ( ! empty( $breadcrumb_html ) ) {
        echo '<nav aria-label="' . esc_attr__( 'Breadcrumbs', 'wpforge' ) . '" class="wpforge-breadcrumbs" style="margin-bottom: 30px; padding: 12px 20px; background: var(--wp--preset--color--surface); border-radius: 6px; font-size: 0.9em; color: var(--wp--preset--color--text-muted); border: 1px solid #eee;">';
        
        // Allowed HTML for breadcrumb trails (links, spans, separators)
        $allowed_html = array(
            'a'    => array( 'href' => array(), 'title' => array() ),
            'span' => array( 'class' => array(), 'property' => array(), 'typeof' => array() ),
            'div'  => array( 'class' => array() ),
            'nav'  => array( 'aria-label' => array() )
        );
        
        echo wp_kses( $breadcrumb_html, $allowed_html );
        echo '</nav>';
    }
}