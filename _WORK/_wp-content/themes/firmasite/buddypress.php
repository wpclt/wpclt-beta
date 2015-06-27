<?php
/**
 * @package firmasite
 */
global $firmasite_settings;

get_header();
 ?>

		<div id="primary" class="content-area clearfix <?php echo $firmasite_settings["layout_primary_class"]; ?>">
			
			<?php do_action( 'open_content' ); ?>
            <?php do_action( 'open_loop' ); ?>

			<?php if ( have_posts() ) : ?>


				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						get_template_part( 'templates/buddypress' );
					?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'templates/no-results', 'index' ); ?>

			<?php endif; ?>
            
			<?php do_action( 'close_loop' ); ?>
            <?php do_action( 'close_content' ); ?>
			
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>