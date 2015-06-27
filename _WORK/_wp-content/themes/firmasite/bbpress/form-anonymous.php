<?php

/**
 * Anonymous User
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php if ( bbp_current_user_can_access_anonymous_user_form() ) : ?>
<div class="row">
    <div class="col-xs-12 col-sm-5 col-md-5">

	<?php do_action( 'bbp_theme_before_anonymous_form' ); ?>

	<fieldset class="bbp-form">
		<legend><?php ( bbp_is_topic_edit() || bbp_is_reply_edit() ) ? _e( 'Author Information', 'firmasite' ) : _e( 'Your information:', 'firmasite' ); ?></legend>

		<?php do_action( 'bbp_theme_anonymous_form_extras_top' ); ?>

		<div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" id="bbp_anonymous_author" class="form-control" placeholder="<?php _e( 'Name (required):', 'firmasite' ); ?>"  value="<?php bbp_author_display_name(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_name" />
			</div>
        </div>

		<div class="form-group">
        	<div class="input-group">
			<span class="input-group-addon"><i class="icon-envelope"></i></span>
			<input type="text" id="bbp_anonymous_email" class="form-control" placeholder="<?php _e( 'Mail (will not be published) (required):', 'firmasite' ); ?>"  value="<?php bbp_author_email(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_email" />
			</div>
        </div>

		<div class="form-group">
        	<div class="input-group">
			<span class="input-group-addon"><i class="icon-globe"></i></span>
			<input type="text" id="bbp_anonymous_website" class="form-control" placeholder="<?php _e( 'Website:', 'firmasite' ); ?>" value="<?php bbp_author_url(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_website" />
			</div>
        </div>

		<?php do_action( 'bbp_theme_anonymous_form_extras_bottom' ); ?>

	</fieldset>

	<?php do_action( 'bbp_theme_after_anonymous_form' ); ?>
    

<?php endif; ?>
