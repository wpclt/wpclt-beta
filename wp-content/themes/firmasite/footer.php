<?php
/**
 * @package firmasite
 */
global $firmasite_settings;
?>
		</div><!--  .row -->
        <?php do_action( 'after_content' ); ?>    
	</div><!-- #main .site-main -->

	<?php get_template_part( 'templates/footer', $firmasite_settings["footer-style"] ); ?>
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

<!-- <?php printf(  '%d queries in %.3f seconds, using %.2fMB memory',
        get_num_queries(),
        timer_stop( 0, 3 ),
        memory_get_peak_usage() / 1024 / 1024
        ); ?> -->
</body>
</html>