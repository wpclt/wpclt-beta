<?php do_action( 'bp_before_group_send_invites_content' ); ?>

<?php
/* Does the user have friends that could be invited to the group? */
if ( bp_get_new_group_invite_friend_list() ) : ?>

	<?php /* 'send-invite-form' is important for AJAX support */ ?>
	<form action="<?php bp_group_send_invite_form_action(); ?>" method="post" id="send-invite-form" class="standard-form margin-top" role="main">

		<div class="invite">
			<?php bp_get_template_part( 'groups/single/invites-loop' ); ?>
		</div>

		<?php /* This is important, don't forget it */ ?>
		<input type="hidden" name="group_id" id="group_id" value="<?php bp_group_id(); ?>" />

	</form><!-- #send-invite-form -->

<?php
/* No eligible friends? Maybe the user doesn't have any friends yet. */
elseif ( 0 == bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

	<div class="clearfix"></div><div id="message" class="info alert alert-info">
		<p class="notice"><?php _e( 'Group invitations can only be extended to friends.', 'firmasite' ); ?></p>
		<p class="message-body"><?php _e( "Once you've made some friendships, you'll be able to invite those members to this group.", 'firmasite' ); ?></p>
	</div>

<?php
/* The user does have friends, but none are eligible to be invited to this group. */
else : ?>

	<div class="clearfix"></div><div id="message" class="info alert alert-info">
		<p class="notice"><?php _e( 'All of your friends already belong to this group.', 'firmasite' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_group_send_invites_content' ); ?>