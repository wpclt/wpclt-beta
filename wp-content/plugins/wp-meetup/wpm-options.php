<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-17
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupOptions {
    
    var $options_name;
    
    var $defaults;
    
    /**
     *
     * @var MIXED STRING/BOOL
     */
    var $updated = FALSE;
    
    /**
     *
     * @var WPMeetup 
     */
    var $core;
    
    var $old_pt_slug;
    var $new_pt_slug;
    
    /**
     * 
     * @param WPMeetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        if (isset($_POST['update'])) {
            $this->updated = $_POST['update'];
        }
        $this->options_name = $core->options_name;
        $this->defaults = array(
            'key' => '',
            'version' => '',
            'wpm_pt' => __('events'),
            'link_name' => __('View On Meetup.com'),
            'support' => FALSE,
            'link_color' => FALSE,
            'link_redirect' => FALSE,
            'include_homepage' => FALSE,
            'display_map' => FALSE,
            'past_months' => 1,
            'future_months' => 3,
            'max_events' => 100,
            'legend' => FALSE,
            'legend_title' => __('Groups:'),
            'single_legend_title' => '',
            'venue' => FALSE,
            'queue_prompt' => time() + 259200,
            'install_count' => 3,
            'auto_delete' => FALSE,
            'delete_old' => FALSE,
            'single_legend' => FALSE,
        );
        if ($this->get_option() == FALSE) {
            $this->set_to_defaults();
        }
    }

    public function set_to_defaults() {
        delete_option($this->options_name);
        foreach ($this->defaults as $key=>$value) {
            $this->update_option($key, $value);
        }
    }
    
    public function update_options() {
        if (isset($_POST['update']) && $_POST['update'] === 'wpm-update-options') {
            if (!isset($_POST['link_color'])) { $_POST['link_color'] = NULL; }        
            if (!isset($_POST['include_homepage'])) { $_POST['include_homepage'] = NULL; }
            if (!isset($_POST['link_redirect'])) { $_POST['link_redirect'] = NULL; }
            if (!isset($_POST['support'])) { $_POST['support'] = NULL; }
            if (!isset($_POST['legend'])) { $_POST['legend'] = NULL; }
            if (!isset($_POST['venue'])) { $_POST['venue'] = NULL; }
            if (!isset($_POST['auto_delete'])) { $_POST['auto_delete'] = NULL; }
            if (!isset($_POST['delete_old'])) { $_POST['delete_old'] = NULL; }
            if (!isset($_POST['single_legend'])) { $_POST['single_legend'] = NULL; }
            $current_settings = $this->get_option();
            $clean_current_settings = array();
            foreach ($current_settings as $k=>$val) {
                if ($k != NULL) {
                    $clean_current_settings[$k] = $val;
                }
            }
            $this->defaults = array_merge($this->defaults, $clean_current_settings);
            $update = array_merge($this->defaults, $_POST);
            $data = array();
            foreach ($update as $key=>$value) {
                if ($key != 'update' && $key != NULL) {
                    if ($value == 'checked') {
                        $data[$key] = $value;
                    }
                    else {
                        $data[$key] = $value;
                    }
                    
                }
            }
            
            // was the post type slug updated?
            if (isset($clean_current_settings['wpm_pt']) && isset($data['wpm_pt']) && $clean_current_settings['wpm_pt'] != $data['wpm_pt']) {
                add_action('plugins_loaded', array(&$this, 'apply_new_post_slug'));
            }
            
            $this->update_option($data);
            $_POST['update'] = NULL;
            $this->updated = 'wpm-update-options';
        }
        else if (isset($_POST['update']) && ($_POST['update'] === 'wpm-update-support' || $_POST['update'] === 'wpm-update-support-prompt')) {
            $current_settings = $this->get_option();
            $this->defaults = array_merge($this->defaults, $current_settings);
            $update = array_merge($this->defaults, $_POST);
            $data = array();
            foreach ($update as $key=>$value) {
                if ($key != 'update' && $key != NULL) {
                    $data[$key] = $value;
                    
                }
            }
            $this->update_option($data);
            $_POST['update'] = NULL;
            $this->updated = 'wpm-update-support';
        }
        else if (isset($_POST['update']) && $_POST['update'] === 'wpm-update-prompt') {
            $current_settings = $this->get_option();
            $this->defaults = array_merge($this->defaults, $current_settings);
            $update = array_merge($this->defaults, $_POST);
            $data = array();
            foreach ($update as $key=>$value) {
                if ($key != 'update' && $key != NULL) {
                    $data[$key] = $value;
                    
                }
            }
            $this->update_option($data);
            $_POST['update'] = NULL;
            $this->updated = 'wpm-update-prompt';
        }
        else if (isset($_POST['update']) && $_POST['update'] === 'wpm-update-events') {
            $_POST['update'] = NULL;
            $this->updated = 'wpm-update-events';
            add_action('init', array(&$this, 'button_force_update'));
        }
        
        else if (isset($_POST['update']) && $_POST['update'] === 'wpm-update-event-deletion') {
            $_POST['update'] = NULL;
            foreach ($_POST as $key=>$value) {
                if ($key != 'update' && $key != NULL && $value == 'checked') {
                    $this->core->event_db->delete($key);
                }
            }
        }
        else if (isset($_POST['update']) && ($_POST['update'] === 'wpm-update-key')) {
            $current_settings = $this->get_option();
            $this->defaults = array_merge($this->defaults, $current_settings);
            $update = array_merge($this->defaults, $_POST);
            $data = array();
            foreach ($update as $key=>$value) {
                if ($key != 'update' && $key != NULL) {
                    $data[$key] = $value;
                }
            }
            $this->update_option($data);
            $_POST['update'] = NULL;
            $this->updated = 'wpm-update-key';
        }
    }
    
    public function button_force_update() {
        $this->core->trigger->execute_update();
    }
    
    public function apply_new_post_slug() {
        $this->new_pt_slug = $this->get_option('wpm_pt');
        
        // get post ids from events db
        $this->core->event_db->select('wp_post_id');
        $id_array = $this->core->event_db->get();
        
        // for each post, run....
        foreach ($id_array as $id) {
            $id = $id->wp_post_id;
            set_post_type($id, $this->new_pt_slug);
        }
        add_action('init', array(&$this, 'nm_rewrite_rules'));
        
        //flush_rewrite_rules(FALSE);
        //nm_reset_permalinks();
        
        
        /*set_post_type($post_id, $this->new_pt_slug);
        $this->core->post_db->select('id');
        $this->core->post_db->select('post_type');
        $this->core->post_db->where('post_type', $this->old_pt_slug);
        $event_posts = $this->core->post_db->get();
        foreach ($event_posts as $event_post) {
            $data = array(
                'id' => $event_post->id,
                'post_type' => $this->new_pt_slug
            );
            $this->core->pt->add_post($data);
        }
        flush_rewrite_rules();*/
    }
    
    public function nm_rewrite_rules() {
        flush_rewrite_rules();
    }
    
    // From metabox v1.0.6
    
    /**
    * Gets an option for an array'd wp_options,
    * accounting for if the wp_option itself does not exist,
    * or if the option within the option
    * (cue Inception's 'BWAAAAAAAH' here) exists.
    * @since  Version 1.0.0
    * @param  string $opt_name
    * @return mixed (or FALSE on fail)
    */
    public function get_option($opt_name = '') {
       $options = get_option($this->options_name);

       // maybe return the whole options array?
       if ($opt_name == '') {
           return $options;
       }

       // are the options already set at all?
       if ($options == FALSE) {
           return $options;
       }

       // the options are set, let's see if the specific one exists
       if (! isset($options[$opt_name])) {
           return FALSE;
       }

       // the options are set, that specific option exists. return it
       return $options[$opt_name];
    }

    /**
    * Wrapper to update wp_options. allows for function overriding
    * (using an array instead of 'key, value') and allows for
    * multiple options to be stored in one name option array without
    * overriding previous options.
    * @since  Version 1.0.0
    * @param  string $opt_name
    * @param  mixed $opt_val
    */
    public function update_option($opt_name, $opt_val = '') {
       // ----- allow a function override where we just use a key/val array
       if (is_array($opt_name) && $opt_val == '') {
           foreach ($opt_name as $real_opt_name => $real_opt_value) {
               $this->update_option($real_opt_name, $real_opt_value);
           }
       } 
       else {
           $current_options = $this->get_option(); // get all the stored options

           // ----- make sure we at least start with blank options
           if ($current_options == FALSE) {
               $current_options = array();
           }

           // ----- now save using the wordpress function
           $new_option = array($opt_name => $opt_val);
           update_option($this->options_name, array_merge($current_options, $new_option));
       }
    }

    /**
    * Given an option that is an array, either update or add
    * a value (or data) to that option and save it
    * @since  Version 1.0.0
    * @param  string $opt_name
    * @param  mixed $key_or_val
    * @param  mixed $value
    */
    public function append_to_option($opt_name, $key_or_val, $value = NULL, $merge_values = TRUE) {
       $key = '';
       $val = '';
       $results = $this->get_option($opt_name);

       // ----- always use at least an empty array!
       if (! $results) {
           $results = array();
       }

       // ----- allow function override, to use automatic array indexing
       if ($value === NULL) {
           $val = $key_or_val;

           // if value is not in array, then add it.
           if (! in_array($val, $results)) {
               $results[] = $val;
           }
       } 
       else {
           $key = $key_or_val;
           $val = $value;

           // ----- should we append the array value to an existing array?
           if ($merge_values && isset($results[$key]) && is_array($results[$key]) && is_array($val)) {
                   $results[$key] = array_merge($results[$key], $val);
           } 
           else {
                   // ----- don't care if key'd value exists. we override it anyway
                   $results[$key] = $val;
           }
       }

       // use our internal function to update the option data!
       $this->update_option($opt_name, $results);
    }
    
    public function update_messages() {
        if ($this->updated == 'wpm-update-options') {
            echo '<div class="updated">The options have been successfully updated.</div>';
            $this->updated = FALSE;
        }
        else if ($this->updated == 'wpm-update-support') {
             echo '<div class="updated">Thank you for supporting the development team! We really appreciate how awesome you are.</div>';
            $this->updated = FALSE;
        }
        else if ($this->updated == 'wpm-update-groups') {
             echo '<div class="updated">Groups have been added.</div>';
            $this->updated = FALSE;
        }
        else if ($this->updated == 'wpm-update-color') {
             echo '<div class="updated">Colors have been saved and the check groups have been deleted.</div>';
            $this->updated = FALSE;
        }
        else if ($this->updated == 'wpm-update-events') {
             echo '<div class="updated">Events have been updated.</div>';
            $this->updated = FALSE;
        }
        else if ($this->updated == 'wpm-update-event-deletion') {
             echo '<div class="updated">Specified inactive events have been deleted.</div>';
            $this->updated = FALSE;
        }
        else if ($this->updated == 'wpm-update-key') {
             echo '<div class="updated">API key has been updated.</div>';
            $this->updated = FALSE;
        }
    }
}