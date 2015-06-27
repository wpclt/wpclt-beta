<?php

/**
 * BuddyPress - Activity Stream Comment
 *
 * This template is used by bp_activity_comments() functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_activity_comment' ); ?>

<li id="acomment-<?php bp_activity_comment_id(); ?>" class="well well-sm">

	<div class="acomment-content pull-left"><?php bp_activity_comment_content(); ?></div>

	<div class="acomment-meta text-right">
		<?php
		/* translators: 1: user profile link, 2: user name, 3: activity permalink, 4: activity timestamp */
		printf( __( '<a href="%1$s">%2$s</a> replied <a href="%3$s" class="activity-time-since"><span class="time-since">%4$s</span></a>', 'firmasite' ), bp_get_activity_comment_user_link(), bp_activity_avatar( 'type=thumb&width=20&user_id=' . bp_get_activity_comment_user_id() ) . bp_get_activity_comment_name(), bp_get_activity_thread_permalink(), bp_get_activity_comment_date_recorded() );
		?>
	</div>
    
	<div class="acomment-options text-right">

		<?php if ( is_user_logged_in() && bp_activity_can_comment_reply( bp_activity_current_comment() ) ) : ?>

			<a href="#acomment-<?php bp_activity_comment_id(); ?>" class="acomment-reply bp-primary-action btn btn-default btn-xs" id="acomment-reply-<?php bp_activity_id(); ?>-from-<?php bp_activity_comment_id(); ?>"><?php _e( 'Reply', 'firmasite' ); ?></a>

		<?php endif; ?>

		<?php if ( bp_activity_user_can_delete() ) : ?>

			<a href="<?php bp_activity_comment_delete_link(); ?>" class="delete acomment-delete confirm bp-secondary-action btn btn-default btn-xs" rel="nofollow"><?php _e( 'Delete', 'firmasite' ); ?></a>

		<?php endif; ?>

		<?php do_action( 'bp_activity_comment_options' ); ?>

	</div>

	<?php bp_activity_recurse_comments( bp_activity_current_comment() ); ?>
</li>

<?php do_action( 'bp_after_activity_comment' ); ?>