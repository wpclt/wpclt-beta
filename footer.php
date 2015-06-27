<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package wpclt
 */
?>

		</div><!-- main container end -->
	</div><!-- page end -->
	<!-- FOOTER -->
	<div class="clearfix"></div>
	<div class="container">
		<footer>
			<div class="col-md-4"><?php dynamic_sidebar( 'footer-widget-1' ); ?></div>
			<div class="col-md-4"><?php dynamic_sidebar( 'footer-widget-2' ); ?></div>
			<div class="col-md-4"><?php dynamic_sidebar( 'footer-widget-3' ); ?></div>
			<div class="col-md-4"><?php dynamic_sidebar( 'footer-widget-4' ); ?></div>
		</footer>
	</div>

	<?php wp_footer(); ?>
	</body>
</html>