<div class="modal firmasite-modal-static"><div class="modal-dialog"><div class="modal-content"><div class="modal-body">
<form action="<?php bp_messages_form_action('compose'); ?>" method="post" id="send_message_form" class="form-horizontal standard-form" role="main">

	<?php do_action( 'bp_before_messages_compose_content' ); ?>

    <div class="form-group">
		<label class="control-label col-md-2" for="send-to-input"><?php _e("Send To (Username or Friend's Name)", 'firmasite' ); ?></label>
        <div class="col-md-10">
            <ul class="first acfb-holder">
                <li>
                    <?php bp_message_get_recipient_tabs(); ?>
                    <input type="text" name="send-to-input" class="send-to-input" id="send-to-input" />
                </li>
            </ul>
        </div>
    </div>

	<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
      <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
          <div class="checkbox">
            <label>
		  		<input type="checkbox" id="send-notice" name="send-notice" value="1" /> <?php _e( "This is a notice to all users.", 'firmasite' ); ?>
            </label>
          </div>
        </div>
      </div>
	<?php endif; ?>

    <div class="form-group">
		<label class="control-label col-md-2" for="subject"><?php _e( 'Subject', 'firmasite' ); ?></label>
        <div class="col-md-10">
			<input type="text" class="form-control" name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />
        </div>
    </div>

    <div class="form-group">
		<label class="control-label col-md-2" for="content"><?php _e( 'Message', 'firmasite' ); ?></label>
        <div class="col-md-10">
			<?php $content = bp_messages_content_value();
            echo firmasite_wp_editor($content , 'message_content', 'content' ); 
            /*
            <textarea name="content" id="message_content" rows="15" cols="40"><?php bp_messages_content_value(); ?></textarea>
            */
            ?>	
        </div>
    </div>


	<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />

	<?php do_action( 'bp_after_messages_compose_content' ); ?>

	<div class="submit">
		<input type="submit" class="btn  btn-primary" value="<?php _e( "Send Message", 'firmasite' ); ?>" name="send" id="send" />
	</div>

	<?php wp_nonce_field( 'messages_send_message' ); ?>
</form>
</div></div></div></div>
<script type="text/javascript">
	document.getElementById("send-to-input").focus();
</script>

