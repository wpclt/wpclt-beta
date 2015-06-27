<?php global $firmasite_settings; ?>
<div id="content">
	<?php do_action( 'template_notices' ); ?>

	<div class="activity no-ajax margin-top" role="main">
		<?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

			<ul id="activity-stream" class="activity-list item-list list-unstyled">
			<?php while ( bp_activities() ) : bp_the_activity(); ?>

				<?php bp_get_template_part( 'activity/entry' ); ?>

			<?php endwhile; ?>
			</ul>

		<?php endif; ?>
	</div>
</div>