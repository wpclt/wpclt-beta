<?php
/**
 * @package firmasite
 */
global $firmasite_settings;
?>
<div id="secondary" class="widget-area clearfix <?php echo $firmasite_settings["layout_secondary_class"]; ?>" role="complementary">
 
    <?php do_action( 'open_sidebar' ); ?>

    <?php dynamic_sidebar( 'site-sidebar' ); ?>

    <?php do_action( 'close_sidebar' ); ?>

</div><!-- #secondary .widget-area -->
