<?php
/**
 * @package firmasite
 */
global $firmasite_settings;

get_header();
 ?>

		<?php do_action( 'bbp_before_main_content' ); ?>

		<div id="primary" class="content-area clearfix <?php echo $firmasite_settings["layout_primary_class"]; ?>">
			
			<?php do_action( 'bbp_template_notices' ); ?>

			<?php do_action( 'open_content' ); ?>
            <?php do_action( 'open_loop' ); ?>

			<?php if ( have_posts() ) : ?>


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Type-specific template for the content.
						   If you want to support Post-Format, i suggest customize loop files with switch()
						 */
						global $post;
						get_template_part( 'templates/bbpress', $post->post_type );
					?>

				<?php endwhile; ?>


			<?php else : ?>

				<?php get_template_part( 'templates/no-results', 'index' ); ?>

			<?php endif; ?>

			<?php do_action( 'close_loop' ); ?>
            <?php do_action( 'close_content' ); ?>
			
		</div><!-- #primary .content-area -->
        
		<?php do_action( 'bbp_after_main_content' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>