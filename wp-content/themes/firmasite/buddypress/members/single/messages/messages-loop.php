<?php do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>

	<?php do_action( 'bp_after_member_messages_pagination' ); ?>

	<?php do_action( 'bp_before_member_messages_threads' ); ?>

	<form action="<?php echo bp_loggedin_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() ?>/bulk-manage/" method="post" id="messages-bulk-management">

	<div class="modal firmasite-modal-static"><div class="modal-dialog"><div class="modal-content"><div class="modal-body">
		<table id="message-threads" class="messages-notices">

			<thead>
				<tr>
					<th scope="col" class="thread-checkbox"><label class="sr-only bp-screen-reader-text" for="select-all-messages"><?php _e( 'Select all', 'firmasite' ); ?></label><input id="select-all-messages" type="checkbox"></th>
					<th scope="col" class="thread-from"><?php _e( 'From', 'firmasite' ); ?></th>
					<th scope="col" class="thread-info"><?php _e( 'Subject', 'firmasite' ); ?></th>
					<th scope="col" class="thread-options"><?php _e( 'Actions', 'firmasite' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

					<tr id="m-<?php bp_message_thread_id(); ?>" class="<?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) : ?> unread<?php else: ?> read<?php endif; ?>">
						<td>
							<input type="checkbox" name="message_ids[]" class="message-check" value="<?php bp_message_thread_id(); ?>" />
						</td>

						<?php if ( 'sentbox' != bp_current_action() ) : ?>
							<td class="thread-from">
								<?php bp_message_thread_avatar( array( 'width' => 25, 'height' => 25 ) ); ?>
								<span class="from"><?php _e( 'From:', 'firmasite' ); ?></span> <?php bp_message_thread_from(); ?>
								<br /><?php bp_message_thread_total_and_unread_count(); ?>
								<span class="label label-default activity"><?php bp_message_thread_last_post_date(); ?></span>
							</td>
						<?php else: ?>
							<td class="thread-from">
								<?php bp_message_thread_avatar( array( 'width' => 25, 'height' => 25 ) ); ?>
								<span class="to"><?php _e( 'To:', 'firmasite' ); ?></span> <?php bp_message_thread_to(); ?>
								<br /><?php bp_message_thread_total_and_unread_count(); ?>
								<span class="label label-default activity"><?php bp_message_thread_last_post_date(); ?></span>
							</td>
						<?php endif; ?>

						<td class="thread-info">
							<p><a class="lead" href="<?php bp_message_thread_view_link(); ?>" title="<?php esc_attr_e( "View Message", 'firmasite' ); ?>"><?php bp_message_thread_subject(); ?></a></p>
							<p class="thread-excerpt"><?php bp_message_thread_excerpt(); ?></p>
						</td>

						<?php do_action( 'bp_messages_inbox_list_item' ); ?>

						<td class="thread-options">
							<?php if ( bp_message_thread_has_unread() ) : ?>
								<a class="btn btn-default btn-xs button read" href="<?php bp_the_message_thread_mark_read_url();?>"><?php _e( 'Read', 'firmasite' ); ?></a>
							<?php else : ?>
								<a class="btn btn-default btn-xs button unread" href="<?php bp_the_message_thread_mark_unread_url();?>"><?php _e( 'Unread', 'firmasite' ); ?></a>
							<?php endif; ?>
							 |
							<a class="btn btn-danger btn-xs button delete" href="<?php bp_message_thread_delete_link(); ?>"><?php _e( 'Delete', 'firmasite' ); ?></a>
						</td>
					</tr>

				<?php endwhile; ?>

			</tbody>

		</table><!-- #message-threads -->
	</div></div></div></div>

		<div class="messages-options-nav form-inline">
			<?php bp_messages_bulk_management_dropdown(); ?>
		</div><!-- .messages-options-nav -->

		<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>
	</form>

	<?php do_action( 'bp_after_member_messages_threads' ); ?>

	<?php do_action( 'bp_after_member_messages_options' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no messages were found.', 'firmasite' ); ?></p>
	</div>

<?php endif;?>

<?php do_action( 'bp_after_member_messages_loop' ); ?>
