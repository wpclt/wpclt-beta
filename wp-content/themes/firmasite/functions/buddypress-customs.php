<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


add_filter( 'bp_get_blog_create_button', 'firmasite_bp_addclass_to_button' );
function firmasite_bp_addclass_to_button($args) {
	$args['link_class'] = $args['link_class'] . ' btn btn-default';
	return $args;
}

add_filter( 'bp_get_send_public_message_button', 'firmasite_bp_addclass_to_button_xs' );
add_filter( 'bp_get_blogs_visit_blog_button', 'firmasite_bp_addclass_to_button_xs' );
add_filter( 'bp_get_add_friend_button', 'firmasite_bp_addclass_to_button_xs' );
add_filter( 'bp_get_group_new_topic_button', 'firmasite_bp_addclass_to_button_xs' );
add_filter( 'bp_get_group_join_button', 'firmasite_bp_addclass_to_button_xs' );
add_filter( 'bp_get_group_create_button', 'firmasite_bp_addclass_to_button_xs' );
add_filter( 'bp_get_send_message_button_args', 'firmasite_bp_addclass_to_button_xs' );
function firmasite_bp_addclass_to_button_xs($args) {
	$args['link_class'] = $args['link_class'] . ' btn btn-default btn-xs';
	return $args;
}




// http://codex.buddypress.org/extending-buddypress/bp-custom-php/
// Removing the links automatically created in a member's profile
add_action( 'bp_init', 'firmasite_remove_xprofile_links' );
function firmasite_remove_xprofile_links() {
    remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 9, 2 );
}


add_filter('bp_members_signup_error_message', "firmasite_bp_members_signup_error_message");
function firmasite_bp_members_signup_error_message($string){
	return '<div class="alert alert-danger">' . $string . '</div>';
}


add_filter( 'bp_get_the_profile_field_options_checkbox', 'firmasite_customize_bp_get_the_profile_field_options_checkbox',10,1);
function firmasite_customize_bp_get_the_profile_field_options_checkbox($html){
	return '<div class="checkbox">' . $html . '</div>';
}
add_filter( 'bp_get_the_profile_field_options_radio', 'firmasite_customize_bp_get_the_profile_field_options_radio',10,1);
function firmasite_customize_bp_get_the_profile_field_options_radio($html){
	return '<div class="radio">' . $html . '</div>';
}

add_filter( 'bp_get_blog_class', 'firmasite_customize_bp_get_blog_class');
function firmasite_customize_bp_get_blog_class($classes){
	$classes[] = 'well';
	$classes[] = 'well-sm';
	$classes[] = 'clearfix';
	return $classes;
}



add_action( 'bp_setup_nav', 'firmasite_buddypress_author_profile_nav' );
function firmasite_buddypress_author_profile_nav() {
	global $bp, $firmasite_settings;
	
	// Determine user to use
	if ( bp_displayed_user_id() )
		$author_id = bp_displayed_user_id();
	elseif ( bp_loggedin_user_id() )
		$author_id = bp_loggedin_user_id();
	else 
		$author_id = false;

	if(isset($author_id) && !empty($author_id)){
		$args = array( 'author' => $author_id, 'post_type' => 'post' );
		$firmasite_buddypress_author_posts = new WP_Query($args); 
	
		if($firmasite_buddypress_author_posts->found_posts > 0){
	
			bp_core_new_nav_item( array( 
				'name' => __( 'Site', 'firmasite' ), 
				'slug' => 'author', 
				'position' => 20, 
				'show_for_displayed_user' => true, 
				'screen_function' => 'firmasite_buddypress_author_profile_screen', 
				'default_subnav_slug' => 'posts', 
				'item_css_id' => 'author'	
			) );
		
			// Author Page link
			$author_page_link = trailingslashit(bp_core_get_user_domain($author_id) . 'site');
		
			bp_core_new_subnav_item(  array( 
				'name' => __( 'Posts', 'firmasite' ), 
				'slug' => 'posts', 
				'parent_url' => $author_page_link, 
				'parent_slug' => 'author', 
				'screen_function' => 'firmasite_buddypress_author_profile_screen', 
				'position' => 20, 
				'item_css_id' => 'posts' 
			) );
		}
	}
}
function firmasite_buddypress_author_profile_screen(){
	add_action( 'bp_template_content', 'firmasite_buddypress_author_profile_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
// Add the Author Posts page to the screen
function firmasite_buddypress_author_profile_screen_content() {
	global $firmasite_settings, $bp;
	
	global $wp_query;
	$temp = $wp_query;
	
	// Determine user to use
	if ( bp_displayed_user_id() )
		$author_id = bp_displayed_user_id();
	elseif ( bp_loggedin_user_id() )
		$author_id = bp_loggedin_user_id();

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array( 'author' => $author_id, 'paged' => $paged, 'post_type' => 'post' );
	$wp_query = new WP_Query($args); 
	if($wp_query->have_posts()):
	while($wp_query->have_posts()) : $wp_query->the_post();
        global $post,$more;
		$more = 0;
        get_template_part( 'templates/loop', $post->post_type );
	endwhile; 
	
	// http://codex.wordpress.org/Function_Reference/paginate_links
	$big = 999999999; // need an unlikely integer
	
	$author_pagination =  paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		//'prev_next'    => false,
		'format' => '?paged=%#%',
		'type'         => 'list',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	) );
	// Remove first page from pagination
	if (strpos($author_pagination,'/author/posts/') === false) 
	$author_pagination = str_replace( "/author/", "/author/posts/", $author_pagination );
	echo '<div class="clearfix"></div>' . $author_pagination;
	
	endif;
	$wp_query = $temp;
    wp_reset_query(); // reset the query
}


// Changes the blog author links on a buddypress site to link to the author's buddypress member profile.
add_filter( 'author_link', "firmasite_buddypress_fix_author_link",10,3);
function firmasite_buddypress_fix_author_link($link, $author_id, $author_nicename) {
   if (function_exists('bp_core_get_user_domain')) {
      $user_link = trailingslashit(bp_core_get_user_domain($author_id) . 'author');
      return $user_link;
   }
   return $link;
}



// Redirecting author page's to buddypress ones
add_filter('template_redirect','firmasite_buddypress_author_redirect_pages');
function firmasite_buddypress_author_redirect_pages(){
	global $bp, $firmasite_settings;

	if (is_author()){
		$author = get_queried_object();
		$author_page_link = trailingslashit(bp_core_get_user_domain($author->ID) . 'author');

        wp_redirect( $author_page_link, 301 );
        exit();
	}
}




function firmasite_bp_message_get_notices() {
	global $userdata;

	$notice = BP_Messages_Notice::get_active();

	if ( empty( $notice ) )
		return false;

	$closed_notices = bp_get_user_meta( $userdata->ID, 'closed_notices', true );

	if ( !$closed_notices )
		$closed_notices = array();

	if ( is_array($closed_notices) ) {
		if ( !in_array( $notice->id, $closed_notices ) && $notice->id ) {
			?>
		<div id="message-<?php echo $notice->id ?>" class="info notice modal fade" rel="n-<?php echo $notice->id ?>" tabindex="-1" role="dialog" aria-hidden="false">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title"><?php echo stripslashes( wp_filter_kses( $notice->subject ) ) ?></h3>
              </div>
              <div class="modal-body">
                <p><?php echo stripslashes( wp_filter_kses( $notice->message) ) ?></p>
              </div>              
              <div class="modal-footer">
                <a href="#" id="close-notice" class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?php _e( 'Close', 'firmasite' ) ?></a>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
		</div>
            <script> 
			jQuery(document).ready(function() {
				jQuery('#message-<?php echo $notice->id ?>').modal('show'); 
				jQuery("#message-<?php echo $notice->id ?> #close-notice").click(function(){ jQuery('#message-<?php echo $notice->id ?>').modal('hide'); });
            });
            </script>
			<?php
		}
	}
}



/**
 * Output the Group members template
 *
 * @since BuddyPress (?)
 *
 * @return string html output
 */
function firmasite_bp_groups_members_template_part() {
	?>
	<div class="item-list-tabs" id="subnav" role="navigation">
		<ul class="nav nav-pills">
			<li class="groups-members-search" role="search">
				<?php bp_directory_members_search_form(); ?>
			</li>

			<?php firmasite_bp_groups_members_filter(); ?>
			<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

		</ul>
	</div>

	<div id="members-group-list" class="group_members dir-list">

		<?php bp_get_template_part( 'groups/single/members' ); ?>

	</div>
	<?php
}

/**
 * Output the Group members filters
 *
 * @since BuddyPress (?)
 *
 * @return string html output
 */
function firmasite_bp_groups_members_filter() {
	?>
	<li id="group_members-order-select" class="last pull-right filter">
		<label for="group_members-order-by"><?php _e( 'Order By:', 'firmasite' ); ?></label>
		<select id="group_members-order-by">
			<option value="last_joined"><?php _e( 'Newest', 'firmasite' ); ?></option>
			<option value="first_joined"><?php _e( 'Oldest', 'firmasite' ); ?></option>

			<?php if ( bp_is_active( 'activity' ) ) : ?>
				<option value="group_activity"><?php _e( 'Group Activity', 'firmasite' ); ?></option>
			<?php endif; ?>

			<option value="alphabetical"><?php _e( 'Alphabetical', 'firmasite' ); ?></option>

			<?php do_action( 'bp_groups_members_order_options' ); ?>

		</select>
	</li>
	<?php
}




/**
 * Customize registration fields
 */
add_filter( 'bp_xprofile_get_field_types', 'firmasite_bp_xprofile_get_field_types' );
function firmasite_bp_xprofile_get_field_types ($fields) {
	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) { 
		$fields['checkbox']			= 'FirmaSite_BP_XProfile_Field_Type_Checkbox';
		$fields['datebox']			= 'FirmaSite_BP_XProfile_Field_Type_Datebox';
		$fields['multiselectbox']	= 'FirmaSite_BP_XProfile_Field_Type_Multiselectbox';
		$fields['number']			= 'FirmaSite_BP_XProfile_Field_Type_Number';
		$fields['url']				= 'FirmaSite_BP_XProfile_Field_Type_URL';
		$fields['radio']			= 'FirmaSite_BP_XProfile_Field_Type_Radiobutton';
		$fields['selectbox']		= 'FirmaSite_BP_XProfile_Field_Type_Selectbox';
		$fields['textarea']			= 'FirmaSite_BP_XProfile_Field_Type_Textarea';
		$fields['textbox']			= 'FirmaSite_BP_XProfile_Field_Type_Textbox';
	}	
	return $fields;
}


if ( bp_is_active( 'xprofile' ) ) {
		
	class FirmaSite_BP_XProfile_Field_Type_Datebox extends BP_XProfile_Field_Type_Datebox {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that we pass to
			// {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				$user_id = (int) $raw_properties['user_id'];
				unset( $raw_properties['user_id'] );
			} else {
				$user_id = bp_displayed_user_id();
			}
	
			$day_r = bp_parse_args( $raw_properties, array(
				'id'   => bp_get_the_profile_field_input_name() . '_day',
				'name' => bp_get_the_profile_field_input_name() . '_day'
			) );
			$day_r['class'] = $day_r['class'] . ' form-control';
	
			$month_r = bp_parse_args( $raw_properties, array(
				'id'   => bp_get_the_profile_field_input_name() . '_month',
				'name' => bp_get_the_profile_field_input_name() . '_month'
			) );
			$month_r['class'] = $month_r['class'] . ' form-control';
	
			$year_r = bp_parse_args( $raw_properties, array(
				'id'   => bp_get_the_profile_field_input_name() . '_year',
				'name' => bp_get_the_profile_field_input_name() . '_year'
			) );
			$year_r['class'] = $year_r['class'] . ' form-control';
			?>
			
			<div class="form-group datebox">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php esc_html_e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
				<div class="col-sm-9 form-inline">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
		
					<select <?php echo $this->get_edit_field_html_elements( $day_r ); ?>>
						<?php bp_the_profile_field_options( array(
							'type'    => 'day',
							'user_id' => $user_id
						) ); ?>
					</select>
		
					<select <?php echo $this->get_edit_field_html_elements( $month_r ); ?>>
						<?php bp_the_profile_field_options( array(
							'type'    => 'month',
							'user_id' => $user_id
						) ); ?>
					</select>
		
					<select <?php echo $this->get_edit_field_html_elements( $year_r ); ?>>
						<?php bp_the_profile_field_options( array(
							'type'    => 'year',
							'user_id' => $user_id
						) ); ?>
					</select>
				</div>
			</div>
		<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Checkbox extends BP_XProfile_Field_Type_Checkbox {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that we pass to
			// {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				$user_id = (int) $raw_properties['user_id'];
				unset( $raw_properties['user_id'] );
			} else {
				$user_id = bp_displayed_user_id();
			} ?>
	
			 <div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<div class="checkbox">
			
						<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
						<?php bp_the_profile_field_options( array(
							'user_id' => $user_id
						) ); ?>
			
					</div>
				</div>
			  </div>
			<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Radiobutton extends BP_XProfile_Field_Type_Radiobutton {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that we pass to
			// {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				$user_id = (int) $raw_properties['user_id'];
				unset( $raw_properties['user_id'] );
			} else {
				$user_id = bp_displayed_user_id();
			} ?>
	
			 <div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<div class="radio">
			
						<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
						<?php bp_the_profile_field_options( array( 'user_id' => $user_id ) );
			
						if ( ! bp_get_the_profile_field_is_required() ) : ?>
			
							<a class="clear-value" href="javascript:clear( '<?php echo esc_js( bp_get_the_profile_field_input_name() ); ?>' );">
								<?php esc_html_e( 'Clear', 'firmasite' ); ?>
							</a>
			
						<?php endif; ?>
			
					</div>
				</div>
			  </div>
			<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Multiselectbox extends BP_XProfile_Field_Type_Multiselectbox {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that we pass to
			// {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				$user_id = (int) $raw_properties['user_id'];
				unset( $raw_properties['user_id'] );
			} else {
				$user_id = bp_displayed_user_id();
			}
	
			$r = bp_parse_args( $raw_properties, array(
				'multiple' => 'multiple',
				'id'       => bp_get_the_profile_field_input_name() . '[]',
				'name'     => bp_get_the_profile_field_input_name() . '[]',
			) ); 
			$r['class'] = isset($r['class']) ? $r['class'] : '';
			$r['class'] = $r['class'] . ' form-control';
			?>
	
			<div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>[]"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
				<div class="col-sm-9">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
					<select <?php echo $this->get_edit_field_html_elements( $r ); ?>>
						<?php bp_the_profile_field_options( array(
							'user_id' => $user_id
						) ); ?>
					</select>
			
					<?php if ( ! bp_get_the_profile_field_is_required() ) : ?>
			
						<a class="clear-value" href="javascript:clear( '<?php echo esc_js( bp_get_the_profile_field_input_name() ); ?>[]' );">
							<?php esc_html_e( 'Clear', 'firmasite' ); ?>
						</a>
			
					<?php endif; ?>
				</div>
			</div>
		<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Selectbox extends BP_XProfile_Field_Type_Selectbox {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that we pass to
			// {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				$user_id = (int) $raw_properties['user_id'];
				unset( $raw_properties['user_id'] );
			} else {
				$user_id = bp_displayed_user_id();
			} ?>
	
			<div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
					<select <?php echo $this->get_edit_field_html_elements( $raw_properties ); ?>>
						<?php bp_the_profile_field_options( array( 'user_id' => $user_id ) ); ?>
					</select>
				</div>
			</div>
			<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Textarea extends BP_XProfile_Field_Type_Textarea {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that certain other fields
			// types pass to {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				unset( $raw_properties['user_id'] );
			}
	
			$r = bp_parse_args( $raw_properties, array(
				'cols' => 40,
				'rows' => 5,
			) ); ?>
	
			<div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
					<?php echo firmasite_wp_editor(bp_get_the_profile_field_edit_value(), bp_get_the_profile_field_input_name(), bp_get_the_profile_field_input_name()); ?>
					<?php /*<textarea <?php echo $this->get_edit_field_html_elements( $r ); ?>><?php bp_the_profile_field_edit_value(); ?></textarea>*/ ?>
				</div>
			</div>
			<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Textbox extends BP_XProfile_Field_Type_Textbox {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that certain other fields
			// types pass to {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				unset( $raw_properties['user_id'] );
			}
	
			$r = bp_parse_args( $raw_properties, array(
				'type'  => 'text',
				'value' => bp_get_the_profile_field_edit_value(),
			) ); 
			$r['class'] = isset($r['class']) ? $r['class'] : '';
			$r['class'] = $r['class'] . ' form-control';
			?>
	
			<div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
					<input <?php echo $this->get_edit_field_html_elements( $r ); ?>>
				</div>
			</div>
			<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_Number extends BP_XProfile_Field_Type_Number {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// user_id is a special optional parameter that certain other fields
			// types pass to {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				unset( $raw_properties['user_id'] );
			}
	
			$r = bp_parse_args( $raw_properties, array(
				'type'  => 'number',
				'value' =>  bp_get_the_profile_field_edit_value()
			) );
			$r['class'] = isset($r['class']) ? $r['class'] : '';
			$r['class'] = $r['class'] . ' form-control';
			?>
	
			<div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
			
					<input <?php echo $this->get_edit_field_html_elements( $r ); ?>>
				</div>
			</div>
			<?php
		}
	}
	
	
	class Firmasite_BP_XProfile_Field_Type_URL extends BP_XProfile_Field_Type_URL {
		public function edit_field_html( array $raw_properties = array() ) {
	
			// `user_id` is a special optional parameter that certain other
			// fields types pass to {@link bp_the_profile_field_options()}.
			if ( isset( $raw_properties['user_id'] ) ) {
				unset( $raw_properties['user_id'] );
			}
	
			$r = bp_parse_args( $raw_properties, array(
				'type'      => 'text',
				'inputmode' => 'url',
				'value'     => esc_url( bp_get_the_profile_field_edit_value() ),
			) ); 
			$r['class'] = isset($r['class']) ? $r['class'] : '';
			$r['class'] = $r['class'] . ' form-control';
			?>
	
			<div class="form-group">
				<label class="control-label col-sm-3" for="<?php bp_the_profile_field_input_name(); ?>">
					<?php bp_the_profile_field_name(); ?>
					<?php if ( bp_get_the_profile_field_is_required() ) : ?>
						<?php esc_html_e( '(required)', 'firmasite' ); ?>
					<?php endif; ?>
				</label>
				<div class="col-sm-9">
					<?php do_action( bp_get_the_profile_field_errors_action() ); ?>
	
					<input <?php echo $this->get_edit_field_html_elements( $r ); ?>>
				</div>
			</div>
	
	
			<?php
		}
	}
	
}







