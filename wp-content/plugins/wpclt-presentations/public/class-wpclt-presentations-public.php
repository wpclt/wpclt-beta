<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.jbwebservices.com
 * @since      1.0.0
 *
 * @package    WPCLT_Presentations
 * @subpackage WPCLT_Presentations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPCLT_Presentations
 * @subpackage WPCLT_Presentations/public
 * @author     Jamie Bowman <info@jbwebservices.com>
 */
class WPCLT_Presentations_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPCLT_Presentations_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPCLT_Presentations_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpclt-presentations-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WPCLT_Presentations_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WPCLT_Presentations_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpclt-presentations-public.js', array( 'jquery' ), $this->version, false );
	}

	public static function test() {

	}

	public function wpclt_presentation_metabox() {
		add_meta_box( 'wpclt-presentations-metabox', 'Presentation Options', array(&$this, 'wpclt_presentation_metabox_html'), 'wpclt-presentations', 'side', 'default');
	}

	public function wpclt_presentation_metabox_html() {
		//wp_die('html');
		//wp_nonce_field( '_wpclt_presentation_nonce', 'wpclt_presentation_nonce' ); ?>

		<p>Options for Presentation</p>
		<p>
			<label for="wpclt_presentation_presenter"><?php _e( 'Presenter', 'presentation' ); ?></label><br>
			<select name="wpclt_presentation_presenter" id="wpclt_presentation_presenter">
				<option <?php echo (presentation_get_meta( 'wpclt_presentation_presenter' ) === 'Jamie Bowman' ) ? 'selected' : '' ?>>Jamie Bowman</option>
				<option <?php echo (presentation_get_meta( 'wpclt_presentation_presenter' ) === 'Brett Bumeter' ) ? 'selected' : '' ?>>Brett Bumeter</option>
				<option <?php echo (presentation_get_meta( 'wpclt_presentation_presenter' ) === 'Test' ) ? 'selected' : '' ?>>Test</option>
			</select>
		</p>	<p>
			<label for="wpclt_presentation_meetup_com_event_link"><?php _e( 'Meetup.com Event Link', 'presentation' ); ?></label><br>
			<input type="text" name="wpclt_presentation_meetup_com_event_link" id="wpclt_presentation_meetup_com_event_link" value="<?php echo presentation_get_meta( 'wpclt_presentation_meetup_com_event_link' ); ?>">
		</p>	<p>
			<label for="wpclt_presentation_date_time"><?php _e( 'Date & Time', 'presentation' ); ?></label><br>
			<input type="text" name="wpclt_presentation_date_time" id="wpclt_presentation_date_time" value="<?php echo presentation_get_meta( 'wpclt_presentation_date_time' ); ?>">
		</p>
		<p>
			<input type="checkbox" name="wpclt_presentation_wp_categories" id="wpclt_presentation_wp_categories" value="wp-categories" <?php echo ( presentation_get_meta( 'wpclt_presentation_wp_categories' ) === 'wp-categories' ) ? 'checked' : ''; ?>>
			<label for="wpclt_presentation_wp_categories"><?php _e( 'WP Categories', 'presentation' ); ?></label>	</p>	<p>
			<label for="wpclt_presentation_picture"><?php _e( 'Picture', 'presentation' ); ?></label><br>
			<input type="text" name="wpclt_presentation_picture" id="wpclt_presentation_picture" value="<?php echo presentation_get_meta( 'wpclt_presentation_picture' ); ?>">
		</p>
		<p>
			<label for="wpclt_presentation_presentation_file_1"><?php _e( 'Presentation File #1', 'presentation' ); ?></label><br>
			<input type="text" name="wpclt_presentation_presentation_file_1" id="wpclt_presentation_presentation_file_1" value="<?php echo presentation_get_meta( 'wpclt_presentation_presentation_file_1' ); ?>">
		</p>
		<p>
		<label for="wpclt_presentation_presentation_file_2"><?php _e( 'Presentation File #2', 'presentation' ); ?></label><br>
		<input type="text" name="wpclt_presentation_presentation_file_2" id="wpclt_presentation_presentation_file_2" value="<?php echo presentation_get_meta( 'wpclt_presentation_presentation_file_2' ); ?>">
		</p>
	<?php
	}

	public function wpclt_presentation_metabox_html_save( $post_id ) {
		wp_die("saving...");
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! isset( $_POST['wpclt_presentation_nonce'] ) || ! wp_verify_nonce( $_POST['wpclt_presentation_nonce'], '_wpclt_presentation_nonce' ) ) return;
		if ( ! current_user_can( 'edit_post' ) ) return;

		if ( isset( $_POST['wpclt_presentation_presenter'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_presenter', esc_attr( $_POST['wpclt_presentation_presenter'] ) );
		if ( isset( $_POST['wpclt_presentation_meetup_com_event_link'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_meetup_com_event_link', esc_attr( $_POST['wpclt_presentation_meetup_com_event_link'] ) );
		if ( isset( $_POST['wpclt_presentation_date_time'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_date_time', esc_attr( $_POST['wpclt_presentation_date_time'] ) );
		if ( isset( $_POST['wpclt_presentation_wp_categories'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_wp_categories', esc_attr( $_POST['wpclt_presentation_wp_categories'] ) );
		else
			update_post_meta( $post_id, 'wpclt_presentation_wp_categories', null );
		if ( isset( $_POST['wpclt_presentation_picture'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_picture', esc_attr( $_POST['wpclt_presentation_picture'] ) );
		if ( isset( $_POST['wpclt_presentation_presentation_file_1'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_presentation_file_1', esc_attr( $_POST['wpclt_presentation_presentation_file_1'] ) );
		if ( isset( $_POST['wpclt_presentation_presentation_file_2'] ) )
			update_post_meta( $post_id, 'wpclt_presentation_presentation_file_2', esc_attr( $_POST['wpclt_presentation_presentation_file_2'] ) );
	}

	public function custom_post_types() {
		// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Presentations', 'Post Type General Name', 'twentythirteen' ),
			'singular_name'       => _x( 'Presentation', 'Post Type Singular Name', 'twentythirteen' ),
			'menu_name'           => __( 'Presentations', 'twentythirteen' ),
			'parent_item_colon'   => __( 'Parent Presentation', 'twentythirteen' ),
			'all_items'           => __( 'All Presentations', 'twentythirteen' ),
			'view_item'           => __( 'View Presentation', 'twentythirteen' ),
			'add_new_item'        => __( 'Add New Presentation', 'twentythirteen' ),
			'add_new'             => __( 'Add Presentation', 'twentythirteen' ),
			'edit_item'           => __( 'Edit Presentation', 'twentythirteen' ),
			'update_item'         => __( 'Update Presentation', 'twentythirteen' ),
			'search_items'        => __( 'Search Presentation', 'twentythirteen' ),
			'not_found'           => __( 'Not Found', 'twentythirteen' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
		);

		// Set other options for Custom Post Type.
		$args = array(
			'label'               => __( 'presentations', 'twentythirteen' ),
			'description'         => __( 'WPCLT Presentations', 'twentythirteen' ),
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// You can associate this CPT with a taxonomy or custom taxonomy.
			'slug'				  => 'presentations',
			'taxonomies'          => array( 'genres' ),
			/* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		// Registering your Custom Post Type
		register_post_type( 'wpclt-presentations', $args );


		/**
		 * Custom Meta Boxes
		 * Meetup.com Link, Look Up Presenter, Date & Time, Location
		 */
		/**
		 * Generated by the WordPress Meta Box generator
		 * at http://jeremyhixon.com/wp-tools/meta-box/
		 */

		function presentation_get_meta( $value ) {
			global $post;

			$field = get_post_meta( $post->ID, $value, true );
			if ( ! empty( $field ) ) {
				return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
			} else {
				return false;
			}
		}

		function presentation_add_meta_box() {
			add_meta_box(
				'presentation-presentation',
				__( 'Presentation', 'presentation' ),
				'wpclt_presentation_html',
				'post',
				'normal',
				'high'
			);
		}

		add_action( 'add_meta_boxes', 'presentation_add_meta_box' );

		function query_user_metadata() {
			// add_user_meta( $user_id, $meta_key, $meta_value, $unique );
			$args = array(
				'meta_key'     => 'Presenter',
				'meta_value'   => '1'
			);
			$presenters = get_users( $args );

			//wp_die("Presenters: " . var_dump($presenters));
		}
		query_user_metadata();

		add_action( 'save_post', array(&$this, 'wpclt_presentation_save' ));

		/*
            Usage: presentation_get_meta( 'wpclt_presentation_presenter' )
            Usage: presentation_get_meta( 'wpclt_presentation_meetup_com_event_link' )
            Usage: presentation_get_meta( 'wpclt_presentation_date_time' )
            Usage: presentation_get_meta( 'wpclt_presentation_wp_categories' )
            Usage: presentation_get_meta( 'wpclt_presentation_picture' )
            Usage: presentation_get_meta( 'wpclt_presentation_presentation_file_1' )
            Usage: presentation_get_meta( 'wpclt_presentation_presentation_file_2' )
        */

	}

	function wpclt_er_save_extra_user_profile_fields( $user_id )
	{
		if ( !current_user_can( 'edit_user', $user_id ) || !is_admin() ) { return false; }
		$post_value = $_POST['wpclt_presenter'] == 'on' ? 1 : 0;
		$result = update_user_meta( $user_id, 'wpclt_presenter', $post_value );
		//wp_die ('Result: ' . $result);
	}

	function wpclt_er_extra_user_profile_fields( $user )
	{ ?>
		<h3>WPCLT Settings</h3>
		<table class="form-table">
			<tr>
				<th><label for="wpclt_presenter">Presenter</label></th>
				<td>
					<input type="checkbox" id="wpclt_presenter" name="wpclt_presenter" <?php echo (esc_attr(  get_the_author_meta( 'wpclt_presenter', $user->ID) ) == '1' ? 'checked' : ''); ?> <?php if (!current_user_can( 'manage_options' )) { echo "disabled"; } ?> />
					<span class="description">Select whether this person is a presenter at the local meet ups.</span>
				</td>
			</tr>
		</table>
	<?php
	}

}
