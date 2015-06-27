<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul class="nav nav-pills">

		<?php do_action( 'bp_group_activity_syndication_options' ); ?>

		<li id="activity-filter-select" class="pull-right form-inline last">
			<label for="activity-filter-by"><?php _e( 'Show:', 'firmasite' ); ?></label>
			<select class="form-control input-sm" id="activity-filter-by">
				<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'firmasite' ); ?></option>

				<?php bp_activity_show_filters( 'group' ); ?>

				<?php do_action( 'bp_group_activity_filter_options' ); ?>
			</select>
		</li>
	</ul>
</div><!-- .item-list-tabs -->

<?php do_action( 'bp_before_group_activity_post_form' ); ?>

<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>

	<?php bp_get_template_part( 'activity/post-form' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_group_activity_post_form' ); ?>
<?php do_action( 'bp_before_group_activity_content' ); ?>

<div class="activity single-group margin-top" role="main">

	<?php bp_get_template_part( 'activity/activity-loop' ); ?>

</div><!-- .activity.single-group -->

<?php do_action( 'bp_after_group_activity_content' ); ?>
