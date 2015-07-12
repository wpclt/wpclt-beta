<?php

/*
Plugin Name: WP Meetup
Plugin URI: http://nuancedmedia.com/wordpress-meetup-plugin/
Description: Pulls events from Meetup.com onto your blog to be displayed in a calendar, list, or various widgets.
Version: 2.2.13
Author: Nuanced Media
Author URI: http://nuancedmedia.com/

Copyright 2013  Nuanced Media

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Just in case
 */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/**
 * Require Library
 */
if (!class_exists('ApiInteraction')) {
    require_once('library/api-interaction.php');
}
if (!class_exists('NMCustomPost')) {
    require_once('library/custom-post.php');
}
require_once('library/helper-functions.php');
if (!class_exists('NMDB')) {
    require_once('library/nmdb.php');
}
if (!class_exists('NMWidget')) {
require_once('library/nm-widget.php');
}

/**
 * Require Short Codes
 */

require_once('includes/shortcodes/calendar.php');
require_once('includes/shortcodes/event-list.php');

/**
 * Require Admin Pages
 */
require_once('includes/admin-pages/admin-page.php');
require_once('includes/admin-pages/main-admin.php');
require_once('includes/admin-pages/options-admin.php');
require_once('includes/admin-pages/groups-admin.php');
require_once('includes/admin-pages/events-admin.php');
require_once('includes/admin-pages/debug-admin.php');

/**
 * Require DBs
 */
require_once('includes/db/events-db.php');
require_once('includes/db/groups-db.php');
require_once('includes/db/posts-db.php');

/**
 * Require Includes
 */
require_once('includes/api.php');
require_once('includes/factory.php');
require_once('includes/pt.php');

/**
 * Require Widgets
 */
require_once('includes/widgets/calendar-widget.php');
require_once('includes/widgets/event-list-widget.php');

/**
 * Require Views
 */
require_once('includes/views/event-view.php');


/**
 * Require Main
 */
require_once('trigger.php');
require_once('wpm-admin.php');
require_once('wpm-options.php');
require_once('backward-capatability.php');

/**
 * Class WP_Meetup
 */
class WPMeetup {

    /**
     *
     * @var WPMeetupOptions
     */
    var $options;

    /**
     *
     * @var WPMeetupPostType
     */
    var $pt;

    /**
     *
     * @var WPMeetupEventsDB
     */
    var $event_db;


    /**
     * @var WPMeetupGroupsDB;
     */
    var $group_db;

    /**
     *
     * @var WPMeetupPostsDB
     */
    var $post_db;

    /**
     *
     * @var WPMeetupAdmin
     */
    var $admin;

    /**
     *
     * @var WPMeetupAPI
     */
    var $api;

    /**
     *
     * @var STRING
     */
    var $post_type;

    /**
     *  @var STRING
     */
    var $options_name = 'wp_meetup_options';

    /**
     *
     * @var ARRAY
     */
    var $events;

    /**
     *
     * @var ARRAY
     */
    var $groups;

    /**
     *
     * @var WPMeetupTrigger
     */
    var $trigger;

    /**
     *
     * @var WPMeetupFactory
     */
    var $factory;

    /**
     *
     * @global string $wpm_core
     */

    public function __construct() {
        global $wpm_core;

        // Create Database
        $this->event_db = new WPMeetupEventsDB();
        $this->group_db = new WPMeetupGroupsDB();
        $this->post_db = new WPMeetupPostsDB();

        // Create - Update - Get options
        $this->options = new WPMeetupOptions($this);
        $this->options->update_options();
        $this->post_type = $this->options->get_option('wpm_pt');

        $this->api = new WPMeetupAPI($this);
        $this->pt = new WPMeetupPostType($this);
        $this->factory = new WPMeetupFactory($this);
        $this->trigger = new WPMeetupTrigger($this);

        new WPMeetupBackCap($this);

        // Update Version Number
        $plugin_data = get_plugin_data( $wpm_core);
        $this_version = $plugin_data['Version'];
        $this->options->update_option('version', $this_version);

        // Execute Admin
        if (is_admin()) {
            $this->admin = new WPMeetupAdmin($this);
        }

        if ($this->options->get_option('include_homepage')) {
            add_filter( 'pre_get_posts', array(&$this, 'include_events_in_loop') );
        }

        add_action('init', array(&$this, 'init'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'add_plugin_settings_link') );


        // Short codes
        add_shortcode('meetup-calendar', array(&$this, 'shortcode_calendar'));
        add_shortcode('wp-meetup-calendar', array(&$this, 'shortcode_calendar'));

        add_shortcode('meetup-list', array(&$this, 'shortcode_list'));
        add_shortcode('wp-meetup-list', array(&$this, 'shortcode_list'));

        // Widgets
        add_action( 'widgets_init', array(&$this, 'register_calendar_widget') );
        add_action( 'widgets_init', array(&$this, 'register_event_list_widget') );

        // Load CSS
        add_action('wp_enqueue_scripts', array(&$this, 'load_styles'), 100);

        // ----- Testing
        // $post_type = $this->pt->pt;
        // dump($post_type, 'Post Type');
        // $post_type = get_post_type_object( $post_type );
        // dump($post_type, 'Post Type Object');

        // $post_type = 'page';
        // dump($post_type, 'Post Type');
        // $post_type = get_post_type_object( $post_type );
        // dump($post_type, 'Post Type Object');


        // global $wp_post_types;
        // dump($wp_post_types);


    }

    public function init() {
        // Retrieve events from DB
        $this->event_db->order_by('event_time');
        $this->event_db->where('status', 'active');
        $this->events = $this->event_db->get();
        $this->groups = $this->group_db->get();

    }

    public function load_styles() {
		$pluginDirectory = trailingslashit(plugins_url(basename(dirname(__FILE__))));
		wp_register_style('wpm-styles', $pluginDirectory . 'css/wp-meetup.css');
		wp_enqueue_style('wpm-styles');
    }

    public function render_nm_credit() {
        $support = $this->options->get_option('support');
        if ($support) {
            ?>
                <div class="credit-line">
                    Supported By:
                    <a href="http://nuancedmedia.com">
                        <img alt="Nuanced Media" src="<?php echo plugins_url() ?>/wp-meetup/images/nuanced_media.png">
                    </a>
                </div>
            <?php
        }
    }
    public function return_nm_credit() {
        $support = $this->options->get_option('support');
        if ($support) {
            $output = '';
            $output .= '<div class="credit-line">' . PHP_EOL;
            $output .= 'Supported By: ' . PHP_EOL;
            $output .= '<a href="http://nuancedmedia.com">' . PHP_EOL;
            $output .= '<img alt="Nuanced Media" src="' . plugins_url() .  '/wp-meetup/images/nuanced_media.png" />' . PHP_EOL;
            $output .= '</a>' . PHP_EOL;
            $output .= '</div>' . PHP_EOL;
            return $output;
        }
    }

    public function include_events_in_loop($query) {
		if (is_home() && $query->is_main_query()) {
			$query->set( 'post_type', array( 'post', $this->options->get_option('wpm_pt')) );
		}
        return $query;
	}

    public function add_plugin_settings_link($links) {
		$settings_link = '<a href="' . admin_url() . 'admin.php?page=wp_meetup_settings">' . __('Settings') . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

    public function shortcode_calendar($atts, $is_widget = FALSE) {
        $calendar = new WPMeetupCalendar($this, $atts, $is_widget);
        if ($is_widget) {
            echo $calendar->execute();
        }
        else {
            return $calendar->execute();
        }
    }

    public function shortcode_list($atts, $is_widget = FALSE) {
        $event_list = new WPMeetupEventList($this, $atts, $is_widget);
        if ($is_widget) {
            echo $event_list->execute();
        }
        else {
            return $event_list->execute();
        }
    }

     public function register_calendar_widget() {
        register_widget( 'WPMeetupCalendarWidget' );
    }

    public function register_event_list_widget() {
        register_widget( 'WPMeetupEventListWidget' );
    }

}

global $wpm_core;
global $wp_meetup;
$wpm_core = ABSPATH . 'wp-content/plugins/wp-meetup/wp-meetup.php';
$wp_meetup = new WPMeetup();
