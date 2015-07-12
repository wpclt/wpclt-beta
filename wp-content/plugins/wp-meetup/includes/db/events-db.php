<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-17
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 *
 * 2014-04-17
 *      - Edit the update_database appropriately
 */

class WPMeetupEventsDB extends NMDB {

    var $sqltable = 'meetup_events';

    public function __construct() {
        global $wpdb;
        $this->sqltable = $wpdb->prefix . $this->sqltable;
        parent::__construct();

    }

    public function create_update_database() {
        /*  Build the Meetup database if and only if it does not already exist */
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE $this->sqltable (
				 id int(11) NOT NULL AUTO_INCREMENT,
				 wp_post_id INT(11) DEFAULT '0',
				 wpm_event_id varchar(200) NOT NULL,
				 event_time text NOT NULL,
				 event_url text NOT NULL,
				 group_id text NOT NULL,
                 event longtext NOT NULL,
                 status text NOT NULL,
				PRIMARY KEY  (id)
				)
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";
        dbDelta($sql);
    }
}
