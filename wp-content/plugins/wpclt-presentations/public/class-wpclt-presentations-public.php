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

		// Set other options for Custom Post Type

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

	}
}
