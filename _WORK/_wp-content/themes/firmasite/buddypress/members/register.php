<?php global $firmasite_settings; ?>
<div id="content">

	<?php do_action( 'bp_before_register_page' ); ?>

     <div class="panel panel-default">
       <div class="panel-body">
		<div class="page" id="register-page">

			<form action="" name="signup_form" id="signup_form" class="standard-form form-horizontal" method="post" enctype="multipart/form-data">

			<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<?php do_action( 'template_notices' ); ?>
				<?php do_action( 'bp_before_registration_disabled' ); ?>

					<p><?php _e( 'User registration is currently not allowed.', 'firmasite' ); ?></p>

				<?php do_action( 'bp_after_registration_disabled' ); ?>
			<?php endif; // registration-disabled signup step ?>

			<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

				<h2><?php _e( 'Create an Account', 'firmasite' ); ?></h2>

				<?php do_action( 'template_notices' ); ?>

				<p><?php _e( 'Registering for this site is easy, just fill in the fields below and we\'ll get a new account set up for you in no time.', 'firmasite' ); ?></p>

				<?php do_action( 'bp_before_account_details_fields' ); ?>

				<div class="register-section" id="basic-details-section">

					<?php /***** Basic Account Details ******/ ?>

					<h4 class="page-header"><?php _e( 'Account Details', 'firmasite' ); ?></h4>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_username"><?php _e( 'Username', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_username_errors' ); ?>
                            <input type="text" class="form-control" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" aria-required="true" <?php bp_form_field_attributes( 'username' ); ?>/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_email"><?php _e( 'Email Address', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_email_errors' ); ?>
                            <input type="email" class="form-control" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" aria-required="true" <?php bp_form_field_attributes( 'email' ); ?>/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_password"><?php _e( 'Choose a Password', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_password_errors' ); ?>
                            <input type="password" class="form-control password-entry" name="signup_password" id="signup_password" value="" aria-required="true" <?php bp_form_field_attributes( 'password' ); ?>/>
                        	<div id="pass-strength-result" class="text-muted"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_password_confirm"><?php _e( 'Confirm Password', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_password_confirm_errors' ); ?>
                            <input type="password" class="form-control password-entry-confirm" name="signup_password_confirm" id="signup_password_confirm" value="" aria-required="true" <?php bp_form_field_attributes( 'password' ); ?>/>
                        </div>
                    </div>

				</div><!-- #basic-details-section -->

				<?php do_action( 'bp_after_account_details_fields' ); ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>
    
                    <?php do_action( 'bp_before_signup_profile_fields' ); ?>
    
                    <div class="register-section" id="profile-details-section">
    
                        <h4  class="page-header"><?php _e( 'Profile Details', 'firmasite' ); ?></h4>
    
                        <?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
                        <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
    
                        <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
    
                            <div<?php bp_field_css_class( 'editfield' ); ?>>
    
                                <?php
                                $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                $field_type->edit_field_html();
    
                                do_action( 'bp_custom_profile_edit_fields_pre_visibility' );
    
                                if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
                                <div class="form-group">
                                  <div class="col-sm-offset-3 col-sm-9">
                                    <p class="field-visibility-settings-toggle text-muted" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                        <?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'firmasite' ), bp_get_the_profile_field_visibility_level_label() ) ?> <a href="#" class="visibility-toggle-link"><?php _ex( 'Change', 'Change profile field visibility level', 'firmasite' ); ?></a>
                                    </p>
    
                                    <div class="field-visibility-settings well well-sm" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>" style="display: none;">
                                        <fieldset>
                                            <legend><?php _e( 'Who can see this field?', 'firmasite' ) ?></legend>
    
                                            <?php bp_profile_visibility_radio_buttons() ?>
    
                                        </fieldset>
                                        <a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'firmasite' ) ?></a>
    
                                    </div>
                                <?php else : ?>
                                <div class="form-group">
                                  <div class="col-sm-offset-3 col-sm-9">
                                    <p class="field-visibility-settings-notoggle text-muted" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                        <?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'firmasite' ), bp_get_the_profile_field_visibility_level_label() ) ?>
                                    </p>
                                <?php endif ?>
    
									<?php do_action( 'bp_custom_profile_edit_fields' ); ?>
    
                                    <div class="description"><?php bp_the_profile_field_description(); ?></div>
                                  </div>
                                </div>

    
                            </div>
    
                        <?php endwhile; ?>
    
                        <input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_field_ids(); ?>" />
    
                        <?php endwhile; endif; endif; ?>
    
                        <?php do_action( 'bp_signup_profile_fields' ); ?>
    
                    </div><!-- #profile-details-section -->
    
                    <?php do_action( 'bp_after_signup_profile_fields' ); ?>
    
                <?php endif; ?>

				<?php if ( bp_get_blog_signup_allowed() ) : ?>

					<?php do_action( 'bp_before_blog_details_fields' ); ?>

					<?php /***** Blog Creation Details ******/ ?>

					<div class="register-section" id="blog-details-section">

						<h4 class="page-header"><?php _e( 'Blog Details', 'firmasite' ); ?></h4>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes, I\'d like to create a new site', 'firmasite' ); ?>
                                </label>
                              </div>
                            </div>
                        </div>                        
                        

						<div id="blog-details"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>

                            <div class="form-group">
                                <label class="control-label col-xs-12 col-md-3" for="signup_blog_url"><?php _e( 'Blog URL', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                                <div class="col-xs-12 col-md-9">
                                    <?php do_action( 'bp_signup_blog_url_errors' ); ?>
                                    <div class="input-group">
										<?php if ( is_subdomain_install() ) : ?>
                                        	<div class="input-group-addon">http://</div>
                                            <input type="text" class="form-control" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" /> 
                                            <div class="input-group-addon">.<?php bp_signup_subdomain_base(); ?></div>
                                        <?php else : ?>
                                           <div class="input-group-addon"><?php echo site_url(); ?>/</div>
                                           <input type="text" class="form-control" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
							
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-md-3" for="signup_blog_title"><?php _e( 'Site Title', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                                <div class="col-xs-12 col-md-9">
                                    <?php do_action( 'bp_signup_blog_title_errors' ); ?>
									<input type="text" class="form-control" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>" />
                                </div>
                            </div>
							
                            <div class="form-group">
                                <div class="col-xs-12 col-md-9 text-right">
                                    <?php do_action( 'bp_signup_blog_privacy_errors' ); ?>
                                    <span><?php _e( 'I would like my site to appear in search engines, and in public listings around this network.', 'firmasite' ); ?>:</span>
                                </div>
                                <div class="col-xs-12 col-md-3">
        
                                    <div class="radio"><label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes', 'firmasite' ); ?></label></div>
                                	<div class="radio"> <label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'No', 'firmasite' ); ?></label></div>
                                </div>
                            </div>


						</div>

					</div><!-- #blog-details-section -->

					<?php do_action( 'bp_after_blog_details_fields' ); ?>

				<?php endif; ?>

				<?php do_action( 'bp_before_registration_submit_buttons' ); ?>

				<div class="submit">
					<input type="submit" class="btn  btn-primary" name="signup_submit" id="signup_submit" value="<?php _e( 'Complete Sign Up', 'firmasite' ); ?>" />
				</div>

				<?php do_action( 'bp_after_registration_submit_buttons' ); ?>

				<?php wp_nonce_field( 'bp_new_signup' ); ?>

			<?php endif; // request-details signup step ?>

			<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

				<h2><?php _e( 'Sign Up Complete!', 'firmasite' ); ?></h2>

				<?php do_action( 'template_notices' ); ?>
				<?php do_action( 'bp_before_registration_confirmed' ); ?>

				<?php if ( bp_registration_needs_activation() ) : ?>
					<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'firmasite' ); ?></p>
				<?php else : ?>
					<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'firmasite' ); ?></p>
				<?php endif; ?>

				<?php do_action( 'bp_after_registration_confirmed' ); ?>

			<?php endif; // completed-confirmation signup step ?>

			<?php do_action( 'bp_custom_signup_steps' ); ?>

			</form>

		</div>
       </div>
     </div>

	<?php do_action( 'bp_after_register_page' ); ?>

</div><!-- #buddypress -->