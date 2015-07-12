<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupGroupsDB extends NMDB {

    var $sqltable = 'meetup_groups';

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
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `group_name` varchar(255) NOT NULL,
				 `group_slug` varchar(255) NOT NULL,
				 `group_id` varchar(255) NOT NULL,
				 `color` varchar(255) NOT NULL,
				PRIMARY KEY  (id)
				)
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";
        dbDelta($sql);
    }
}
