<?php

class WPMeetupBackCap {
    
    /**
     *
     * @var WPMeetup
     */
    var $core;
    
    /**
     *
     * @var WPMeetupAPI 
     */
    var $api;
    
    /**
     *
     * @var WPMeetupGroupsDB
     */
    var $db;
    
    /**
     *
     * @var ARRAY
     */
    var $options = array();
    
    /**
     * 
     * @global type $wpm_core
     * @param WPMeetup $core
     */
    public function __construct($core) {
        global $wpm_core;
        $this->core = $core;
        $this->db = $this->core->group_db;
        $plugin_data = get_plugin_data( $wpm_core);
        $this_version = $plugin_data['Version'];
        $this_version_array = explode('.', $this_version);
        if ($core->options->get_option('version')) {
            $version = $core->options->get_option('version');
            
            // If version is this version then exist out of here.
            if ($this_version == $version) {return;}
            $version = explode('.', $version);
            
            // If version is after 2.2.0
            if ($version[0] == '2' && $version[1] >= 2) {
                $update = array(
                    'queue_prompt' => time() + 259200,
                    'install_count' => 3,
                );
                $core->options->update_option($update);
            }
            
            // If version is 2.2.x, such that x < 7
            if ($version[0]=='2' && $version[1]=='2' && $version[2] < '7') {
                $this->version_2_2_x_to_2_2_7();
                $this->core->options->update_option('auto_delete', FALSE);
                $this->core->options->update_option('delete_old', FALSE);
                $this->core->options->update_option('single_legend', FALSE);
                $this->core->options->update_option('single_legend_title', 'Events from:');
                $this->core->trigger->execute_update();
            }
            return;
        }
        
        // Prior to 2.2.0 update handling.
        if($this_version != $core->options->get_option('version')) {
            $this->api = $this->core->api;
            $past_version = get_option('wp_meetup_version');
            if ($past_version and (!$core->options->get_option('version'))) { 
                $past_version = explode('.', $past_version['version']);
                if ($past_version[0] == '2') {
                    $this->version_2_x_to_2_2();
                }
            }
            else if ($past_version[0] == '1') {
                $this->version_1_5_to_2_2();
            }
            $this->core->trigger->update_events();
        }
        $this->core->options->update_option( 'version', $this_version);
    }
    
    /**
     * Update from 2.2.X such that X < 7
     */
    private function version_2_2_x_to_2_2_7 () {
        $this->core->event_db->create_update_database();
    }
    
    /**
     * Updates from version 2.X.X to 2.2.X
     */
    private function version_2_x_to_2_2 () {
        
        $this->core->event_db->delete_table();
        $this->core->event_db->create_update_database();
        
        $this->core->options->set_to_defaults();
        
        $options = get_option('meetup_options');
        if (isset($options['apikey'])) {
            $this->core->options->update_option('key', $options['apikey']);
        }
        $credit = get_option('wpm_credit_permission');
        if (isset($credit['permission_value'])) {
            $this->core->options->update_option( 'support', $credit['permission_value']);
        }
        $color = get_option('wp_color_permission');
        if (isset($color['color_permission'])) {
            $this->core->options->update_option('link_color', $color['color_permission']);
        }
        $redirect = get_option('wp_redirect_link');
        if (isset($redirect['redirect_link'])) {
            $this->core->options->update_option('link_redirect', $redirect['redirect_link']);
        }
        $homepage = get_option('wp_include_on_homepage');
        if (isset($homepage['include_homepage'])) {
            $this->core->options->update_option( 'include_homepage', $homepage['include_homepage']);
        }
        $performance = get_option('wp_performance');
        if (isset($performance['past_months_queried']) && isset($performance['future_months_queried']) && isset($performance['max_event'])) {
            $this->core->options->update_option( 'past_months', $performance['past_months_queried']);
            $this->core->options->update_option( 'future_months', $performance['future_months_queried']);
            $this->core->options->update_option( 'max_events', $performance['max_event']);
        }
        
        $groups = get_option('wp_meetup_groups');
        if ($groups) {
            foreach ($groups as $group) {
                $group = $group['name'];
                $this->add_old_group($group);
            }
            
        }
    }
    
    /**
     * Adds an individual group to the DB from old option storage
     * @param type $group
     */
    private function add_old_group($group) {
        $results = $this->api->get_results(array('group_urlname'=> $group), 'groups');
        if (nm_is_json($results)) {
            $results = json_decode($results);
        } else if (!isset($results->results['0'])) {
            echo '<div class="error">WP Meetup: Please enter a correct group urlname. Returned results not JSON.</div>';
            return;
        }
        $result = $results->results['0'];
        if (is_null($result)) {
            echo '<div class="error">WP Meetup: Please enter a correct group urlname. Error in API request.</div>';
            return;
        }
        $colors = get_option('wp_meetup_colors');
        if (isset($color['colors'])) {
            $colors = $colors['colors'];
        }
        if (isset($colors['wpm_calendar_' . $result->urlname . '_color'])) {
            $group_data = array(
                'group_name' => $result->name,
                'group_slug' => $result->urlname,
                'group_id' => $result->id,
                'color' => $colors['wpm_calendar_' . $result->urlname . '_color'],
            );
        }
        else {
            $group_data = array(
                'group_name' => $result->name,
                'group_slug' => $result->urlname,
                'group_id' => $result->id,
                'color' => '#656565',
            );
        }
        $this->core->group_db->select('id');
        $this->core->group_db->where($group_data);
        $id = $this->core->group_db->get();
        if (empty($id)) {$id = NULL;}
        else{$id = $id[0];$id = $id->id;}
        $this->db->save($group_data, $id);
    }
    
    /**
     * Updates from version 1.5.X to version 2.2.X
     * @global type $wpdb
     */
    private function version_1_5_to_2_2() {
        global $wpdb;
        $options = get_option('wp_meetup_options');
        delete_option('wp_meetup_options');
        $this->core->options->set_to_defaults();
        if (isset($options['api_key'])) {
            $options['api_key'] = trim($options['api_key']);
            if ($options['api_key'] == '1') {
                $options['api_key'] = 'checked';
            }
        } 
        else {
            $option['api_key'] = FALSE;
        }
        if (isset($options['include_home_page'])) {
            $options['include_home_page'] = trim($options['include_home_page']);
            if ($options['include_home_page'] == '1') {
                $options['include_home_page'] = 'checked';
            }
        }
        else {
            $option['include_home_page'] = FALSE;
        }
        if (isset($options['show_nm_link'])) {
            $options['show_nm_link'] = trim($options['show_nm_link']);
            if ($options['show_nm_link'] == '1') {
                $options['show_nm_link'] = 'checked';
            }
        }
        else {
            $option['show_nm_link'] = FALSE;
        }
        $this->core->options->update_option('key', $options['api_key']);
        $this->core->options->update_option('include_homepage', $options['include_home_page']);
        $this->core->options->update_option('support', trim($options['show_nm_link']));
        
        /**
         * Example dump of get_option('wp_meetup_options) version 1.5.3
         *    Dump => array(7) {
         *     
         *        ["api_key"] => string(31) "272f261647f463f5b6b4c41a745f28 "
         *        ["publish_buffer"] => string(7) "2 weeks"
         *        ["include_home_page"] => bool(true)
         *        ["display_event_info"] => bool(true)
         *        ["use_rsvp_button"] => bool(false)
         *        ["button_script_url"] => bool(false)
         *        ["show_nm_link"] => bool(true)
         *    }
        */
        
        $table = $wpdb->prefix . 'wpmeetup_groups';
        $results = $wpdb->get_results("SELECT url_name FROM " . $table);
        foreach ($results as $group) {
            $this->add_old_group($group->url_name);
        }
    }
    
}