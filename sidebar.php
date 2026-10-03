<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WPForge
 */

// If no active widgets are set for 'sidebar-1', return early to avoid empty markup.
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="secondary" class="widget-area" style="background: #f9f9f9; padding: 30px; border-radius: 8px; border: 1px solid #eee;">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->