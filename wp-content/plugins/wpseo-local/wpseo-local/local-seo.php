<?php
/*
Plugin Name: Local SEO for WordPress SEO by Yoast
Version: 1.2.2.2
Plugin URI: https://yoast.com/wordpress/local-seo/
Description: This Local SEO module adds all the needed functionality to get your site ready for Local Search Optimization
Author: Joost de Valk and Arjan Snaterse
Author URI: https://yoast.com

Copyright 2012-2014 Joost de Valk & Arjan Snaterse

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * All functionality for fetching location data and creating an KML file with it.
 *
 * @package    WordPress SEO
 * @subpackage WordPress SEO Local
 */

define( 'WPSEO_LOCAL_VERSION', '1.2.2.2' );

$options = get_option('wpseo_local');
if ( isset( $options['license'] ) && !empty( $options['license'] ) ) {
	if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		// load our custom updater
		include( dirname( __FILE__ ) . '/includes/EDD_SL_Plugin_Updater.php' );
	}

	$edd_updater = new EDD_SL_Plugin_Updater( 'https://yoast.com', __FILE__, array(
			'version'     => WPSEO_LOCAL_VERSION, // current version number
			'license'     => trim( $options['license'] ), // license key (used get_option above to retrieve from DB)
			'item_name'   => 'Local SEO for WordPress', // name of this plugin
			'author'      => 'Joost de Valk' // author of this plugin
		)
	);
}


// Load text domain
add_action( 'init', 'wpseo_local_load_textdomain' );
function wpseo_local_load_textdomain() {
	load_plugin_textdomain( 'yoast-local-seo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/**
 * Initialize the Local SEO module on plugins loaded, so WP SEO should have set its constants and loaded its main classes.
 *
 * @since 0.2
 */
function wpseo_local_seo_init() {
	global $WPSEO_Local_Search_Admin, $WPSEO_Frontend_Local;

	if ( defined( 'WPSEO_VERSION' ) ) {
		require_once 'includes/wpseo-local-functions.php';
		require_once 'includes/ajax-functions.php';
		require_once 'classes/wpseo-local-admin.class.php';
		require_once 'classes/wpseo-local-frontend.class.php';
		require_once 'classes/wpseo-local-storelocator.class.php';
		require_once 'widgets/widget-show-address.php';
		require_once 'widgets/widget-show-map.php';
		require_once 'widgets/widget-show-openinghours.php';
		require_once 'widgets/widget-storelocator-form.php';
		
		$WPSEO_Local_Search_Admin = new WPSEO_Local_Search_Admin();
		$WPSEO_Frontend_Local = new WPSEO_Frontend_Local();
		$WPSEO_Storelocator = new WPSEO_Storelocator();
	}
	else {
		add_action( 'all_admin_notices', 'wpseo_local_missing_error' );
	}
}
add_action( 'plugins_loaded', 'wpseo_local_seo_init' );

require_once 'classes/wpseo-local-admin.class.php';
register_activation_hook( __FILE__, array( 'WPSEO_Local_Search_Admin', 'activate_license' ) );

/**
 * Throw an error if WordPress SEO is not installed.
 *
 * @since 0.2
 */
function wpseo_local_missing_error() {
	echo '<div class="error"><p>Please <a href="' . admin_url( 'plugin-install.php?tab=search&type=term&s=wordpress+seo&plugin-search-input=Search+Plugins' ) . '">install &amp; activate WordPress SEO by Yoast</a> and then go to the Local SEO section to enable the Local SEO module to work.</p></div>';
}
