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
class WPCLT_Presentations_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_register_style('jquery-ui-css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui-css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpclt-presentations-admin.css', array(), $this->version, 'all' );

		wp_register_style('jquery-ui-timepicker-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui-timepicker-addon.css');
		wp_enqueue_style( 'jquery-ui-timepicker-css' );
	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_register_script( 'jquery-ui-js', ( 'https://code.jquery.com/ui/1.11.4/jquery-ui.js' ), false, null, true );
		wp_enqueue_script( 'jquery-ui-js' );

		wp_register_script( 'jquery-timepicker-js', ( plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js' ), false, null, true );
		wp_enqueue_script( 'jquery-timepicker-js' );

		wp_register_script( 'wpclt-presentations-admin', ( plugin_dir_url( __FILE__ ) . 'js/wpclt-presentations-admin.js' ), false, null, true );
		wp_enqueue_script( 'wpclt-presentations-admin' );
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpclt-presentations-admin.js', array( 'jquery' ), $this->version, false );

	}

}
