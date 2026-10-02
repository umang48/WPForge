<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WPForge
 */

// If no active widgets are set, return early.
// if ( ! is_active_sidebar( 'sidebar-1' ) ) {
//     return;
// }
?>

<aside id="secondary" class="widget-area" style="background: #f9f9f9; padding: 30px; border-radius: 8px; border: 1px solid #eee;">
    <p style="color: #666; font-style: italic;">
        <?php esc_html_e( 'Sidebar widgets will appear here once registered.', 'wpforge' ); ?>
    </p>
</aside><!-- #secondary -->