<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.jbwebservices.com
 * @since      1.0.0
 *
 * @package    WPCLT_Presentations
 * @subpackage WPCLT_Presentations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPCLT_Presentations
 * @subpackage WPCLT_Presentations/admin
 * @author     Jamie Bowman <info@jbwebservices.com>
 */
class WPCLT_Presentations_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
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

		wp_register_style('jquery-ui-css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('jquery-ui-css');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpclt-presentations-admin.css', array(), $this->version, 'all');

		wp_register_style('jquery-ui-timepicker-css', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css');
		wp_enqueue_style('jquery-ui-timepicker-css');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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
		wp_register_script('jquery-ui-js', ('https://code.jquery.com/ui/1.11.4/jquery-ui.js'), false, null, true);
		wp_enqueue_script('jquery-ui-js');

		wp_register_script('jquery-timepicker-js', (plugin_dir_url(__FILE__) . 'js/jquery-ui-timepicker-addon.js'), false, null, true);
		wp_enqueue_script('jquery-timepicker-js');

		wp_register_script('wpclt-presentations-admin', (plugin_dir_url(__FILE__) . 'js/wpclt-presentations-admin.js'), false, null, true);
		wp_enqueue_script('wpclt-presentations-admin');
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpclt-presentations-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wpclt_presentation_metabox()
	{
		add_meta_box('wpclt-presentations-metabox', 'Presentation Options', array(&$this, 'wpclt_presentation_metabox_html'), 'wpclt-presentations', 'side', 'default');
	}

	public function wpclt_presentation_metabox_html()
	{
		wp_nonce_field( '_wpclt_presentation_nonce', 'wpclt_presentation_nonce' );
		wp_reset_query();
		$user_query = new WP_User_Query(
			array('meta_key' => 'wpclt_presenter', 'meta_value' => '1')
		);
		$users = $user_query->get_results();

		//var_dump ($users);
		?>

		<p>
			<label for="wpclt_presentation_presenter"><?php _e('Presenter', 'presentation'); ?></label><br>
			<select name="wpclt_presentation_presenter" id="wpclt_presentation_presenter">
				<option value=""></option>
				<?php foreach ($users as $user) { ?>
					<option value="<?php echo $user->ID; ?>" <?php echo (presentation_get_meta('wpclt_presentation_presenter') === 'Jamie Bowman') ? 'selected' : '' ?>>
						<?php echo $user->display_name; ?>
					</option>
				<?php } ?>
			</select>
		</p>    <p>
		<label
			for="wpclt_presentation_meetup_com_event_link"><?php _e('Meetup.com Event Link', 'presentation'); ?></label><br>
			<input type="text" name="wpclt_presentation_meetup_com_event_link" id="wpclt_presentation_meetup_com_event_link"
			   value="<?php echo presentation_get_meta('wpclt_presentation_meetup_com_event_link'); ?>">
		</p>
		<p>
		<label for="wpclt_presentation_date_time"><?php _e('Date & Time', 'presentation'); ?></label><br>
		<input type="text" name="wpclt_presentation_date_time" id="wpclt_presentation_date_time"
			   class="wpclt-presentations-date-picker"
			   value="<?php echo presentation_get_meta('wpclt_presentation_date_time'); ?>">
	</p>
		<p>
			<input type="checkbox" name="wpclt_presentation_wp_categories" id="wpclt_presentation_wp_categories"
				   value="wp-categories" <?php echo (presentation_get_meta('wpclt_presentation_wp_categories') === 'wp-categories') ? 'checked' : ''; ?>>
			<label for="wpclt_presentation_wp_categories"><?php _e('WP Categories', 'presentation'); ?></label></p>
		<p>
			<label for="wpclt_presentation_picture"><?php _e('Picture', 'presentation'); ?></label><br>
			<input type="text" name="wpclt_presentation_picture" id="wpclt_presentation_picture"
				   value="<?php echo presentation_get_meta('wpclt_presentation_picture'); ?>">
		</p>
		<p>
			<label
				for="wpclt_presentation_presentation_file_1"><?php _e('Presentation File #1', 'presentation'); ?></label><br>
			<input type="text" name="wpclt_presentation_presentation_file_1" id="wpclt_presentation_presentation_file_1"
				   value="<?php echo presentation_get_meta('wpclt_presentation_presentation_file_1'); ?>">
		</p>
		<p>
			<label
				for="wpclt_presentation_presentation_file_2"><?php _e('Presentation File #2', 'presentation'); ?></label><br>
			<input type="text" name="wpclt_presentation_presentation_file_2" id="wpclt_presentation_presentation_file_2"
				   value="<?php echo presentation_get_meta('wpclt_presentation_presentation_file_2'); ?>">
		</p>
	<?php
	}

	public function wpclt_presentation_metabox_html_save( $post )
	{
		wp_die("saving...");
		/*
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!isset($_POST['wpclt_presentation_nonce']) || !wp_verify_nonce($_POST['wpclt_presentation_nonce'], '_wpclt_presentation_nonce')) return;
		if (!current_user_can('edit_post')) return;

		if (isset($_POST['wpclt_presentation_presenter']))
			update_post_meta($post_id, 'wpclt_presentation_presenter', esc_attr($_POST['wpclt_presentation_presenter']));
		if (isset($_POST['wpclt_presentation_meetup_com_event_link']))
			update_post_meta($post_id, 'wpclt_presentation_meetup_com_event_link', esc_attr($_POST['wpclt_presentation_meetup_com_event_link']));
		if (isset($_POST['wpclt_presentation_date_time']))
			update_post_meta($post_id, 'wpclt_presentation_date_time', esc_attr($_POST['wpclt_presentation_date_time']));
		if (isset($_POST['wpclt_presentation_wp_categories']))
			update_post_meta($post_id, 'wpclt_presentation_wp_categories', esc_attr($_POST['wpclt_presentation_wp_categories']));
		else
			update_post_meta($post_id, 'wpclt_presentation_wp_categories', null);
		if (isset($_POST['wpclt_presentation_picture']))
			update_post_meta($post_id, 'wpclt_presentation_picture', esc_attr($_POST['wpclt_presentation_picture']));
		if (isset($_POST['wpclt_presentation_presentation_file_1']))
			update_post_meta($post_id, 'wpclt_presentation_presentation_file_1', esc_attr($_POST['wpclt_presentation_presentation_file_1']));
		if (isset($_POST['wpclt_presentation_presentation_file_2']))
			update_post_meta($post_id, 'wpclt_presentation_presentation_file_2', esc_attr($_POST['wpclt_presentation_presentation_file_2']));
		*/
	}

	function wpclt_users_admin_presenter_column( $columns ) {
		$columns['wpclt_presenter'] = 'Presenter';
		return $columns;
	}

	function wpclt_users_admin_presenter_column_content($value, $column_name, $user_id) {
		$user = get_userdata( $user_id );
		if ( 'wpclt_presenter' == $column_name ) {
			$presenter_value = get_user_meta( $user_id, 'wpclt_presenter', true );
			if ($presenter_value == '1') {
				return 'Yes';
			} else {
				return '';
			}
		}
		return $value;
	}

	function wpclt_er_extra_user_profile_fields($user)
	{ ?>
		<h3>WPCLT Settings</h3>
		<table class="form-table">
			<tr>
				<th><label for="wpclt_presenter">Presenter</label></th>
				<td>
					<input type="checkbox" id="wpclt_presenter" name="wpclt_presenter" <?php echo(esc_attr(get_the_author_meta('wpclt_presenter', $user->ID)) == '1' ? 'checked' : ''); ?> <?php if (!current_user_can('manage_options')) {
						echo "disabled";
					} ?> />
					<span class="description">Select whether this person is a presenter at the local meet ups.</span>
				</td>
			</tr>
		</table>
	<?php
	}

	function wpclt_er_save_extra_user_profile_fields( $user_id )
	{
		if (!current_user_can('edit_user', $user_id) || !is_admin()) {
			return false;
		}
		$post_value = $_POST['wpclt_presenter'] == 'on' ? 1 : 0;
		$result = update_user_meta($user_id, 'wpclt_presenter', $post_value);
	}

}
