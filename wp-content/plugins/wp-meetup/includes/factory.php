<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupFactory {

    /**
     * @var WPMeetup $core;
     */
    var $core;

    /**
     * @var ARRAY $event_id_array
     */
    var $event_id_array = array();

    /**
    * @var ARRAY $post_id_array
    */
    var $post_id_array = array();

    public function __construct($core) {
        $this->core = $core;
    }

    public function query() {
        $this->core->group_db->select('group_slug');
        $slug_array = $this->core->group_db->get();
        if (is_array($slug_array)) {
            foreach ($slug_array as $slug_object) {
                $slug = $slug_object->group_slug;
                $error = $this->retrieve_and_store($slug);
                if ($error) {
                    return 'error';
                }
            }
        }
        return null;
    }

    public function retrieve_and_store($slug) {
        $events_object = $this->core->api->get_results($slug);
        if (is_null($events_object)) {
            if (is_admin()) {
                echo '<div class="error">' . __('WP Meetup: Query was not made. Please email plugins@nuancedmedia.com with a screenshot of your debug page as well as your options page.') . '</div>';
            }
            return TRUE;
        }
        if (isset($events_object->problem)) {
            if (is_admin()) {
                echo '<div class="error">' . __('Please double check that you are connected to the internet and your API key is correct.') . '</div>';
            }
            return TRUE;
        }
        $events_array = $events_object->results;
        if (is_null($events_array)) {
            if (is_admin()) {
                echo '<div class="error">' . __('There was an error querying your events. Please try again at a later time.') . '</div>';
            }
            return TRUE;
        }
        foreach ($events_array as $event) {
            $this->store_individual($event);
        }
        return FALSE;
    }

    public function store_individual($event) {

        $time = $event->time + $event->utc_offset;
        $event->time = $time/1000;
        if (gettype($event->time) == 'double') {
            $event->time = intval($event->time);
        }
        $event->utc_offset = 0;
        $data = array(
            'wpm_event_id' => $event->id,
            'event_time' => $event->time,
            'event_url' => $event->event_url,
            'group_id' => $event->group->id,
            'event' => serialize($event),
            'status' => 'active',
        );
        $this->core->event_db->select('id');
        $this->core->event_db->where('wpm_event_id', $event->id);
        $id = $this->core->event_db->get();
        if (empty($id)) {
            $id = NULL;
            $this->core->event_db->save($data, $id);

            // ----- Now lets get the ID of the newly added event
            // ----- And we can use that  for the event filtering.
            $this->core->event_db->select('id');
            $this->core->event_db->where('wpm_event_id', $event->id);
            $id = $this->core->event_db->get();
            $id = $id[0];
            $id = $id->id;
        }
        else{
            $id = $id[0];
            $id = $id->id;
            $this->core->event_db->save($data, $id);
        }
        $this->event_id_array[] = $id;
        $this->core->event_db->save($data, $id);
    }

    public function update_posts() {
        $event_array = $this->core->event_db->get();
        if (!is_array($event_array)) {$event_array = array();}
        foreach ($event_array as $event) {
            $event_raw = $event->event;
            $event_raw = unserialize($event_raw);
            if ($event_raw) {
                $post_name = strtolower(str_replace(' ', '-', str_replace('-', '', $event_raw->name)));
                $post_name = str_replace(',', '', $post_name);
                $post_name = str_replace(':', '', $post_name);
                $post_array = array(
                    'post_title' => $event_raw->name,
                    'post_content' => $this->build_meetup_backlink($event) . $event_raw->description,
                    //'post_name' => $post_name,
                    'post_type' => $this->core->post_type,
                    'post_status' => 'publish',
                );
            }
            else {
                echo '<div class="error">' . __('WP Meetup: An error occured while updating posts. Pleaes demand an event update on the main settings page.') . '</div>';
                return;
            }
            if ($event->wp_post_id) {
                $post_array['ID'] = $event->wp_post_id;
                $post_array['post_status'] = 'publish';
            }
            $id = $this->core->pt->add_post($post_array);
            $event->wp_post_id = $id;
            foreach($event as $key=>$val) {
                $data[$key] = $val;
            }
            $this->core->event_db->save($data, $event->id);
        }
    }

    /**
     * Build the on page Meetup backlink container
     * @param type $event
     * @return string
     */
    function build_meetup_backlink($event) {
        $event_raw = unserialize($event->event);
        $event_link = $event->event_url;
        $event_date = date('l, d M Y g:i',$event->event_time);
        $event_suffix = date('H',$event->event_time);
        if ($event_suffix >= 12) {
            $event_suffix = ' PM';
        }
        else {
            $event_suffix = ' AM';
        }
        $output = '';
        $output .= '<div class="meetup-backlink">' . PHP_EOL;
        $output .= '<div class="button-wrapper">' . PHP_EOL;
        $output .= '<a href="' . $event_link  . '" class="button">' . __($this->core->options->get_option('link_name')) . '</a>' . PHP_EOL;
        $output .= '</div>' . PHP_EOL;
        $output .= '<div class="date-wrapper">' . PHP_EOL;
        $output .= '<h3>Date</h3>' . PHP_EOL;
        $output .= '<p>' . $event_date . $event_suffix . '</p>' . PHP_EOL;
        $output .= '</div>' . PHP_EOL;
        if ($this->core->options->get_option('venue')) {
            if (isset($event_raw->venue)) {
                $event_venue = $event_raw->venue;
                $event_location_name = '<strong>' . $event_raw->venue->name . '</strong><br />';
                $event_location = $event_location_name;
                if (isset($event_raw->venue->address_1) && isset($event_raw->venue->city) && isset($event_raw->venue->state) && isset($event_raw->venue->zip)) {
                    $event_location_address = __($event_raw->venue->address_1) . '<br />' . __($event_raw->venue->city) . ', ' . __($event_raw->venue->state) . ' ' . __($event_raw->venue->zip);
                    $event_location .= $event_location_address;
                }
                $event_map = '<a href="https://maps.google.com/?q=' . $event_raw->venue->lat . ',' . $event_raw->venue->lon . '">View Map</a>';
                $output .= '<div class="meetup-backlink-venue">' . PHP_EOL;
                $output .= '<h3>Venue</h3>' . PHP_EOL;
                $output .= '<p>' . $event_location . '</p>' . PHP_EOL;
                $output .= '<div class="meetup-backlink-map">' . $event_map . '</div>' . PHP_EOL;
                $output .= '</div>' . PHP_EOL;

            }
        }
        $output .= $this->core->return_nm_credit();
        $output .= '</div>' . PHP_EOL;

        return $output;
    }

    /**
     * Filter through events and make events that are in the DB but not returned
     * in the query, then make event inactive
     */
    public function filter_old_events() {

        // ----- Get the UNIX code for the beginning and end of query parameters
        $unix_array = $this->get_unix_for_query_options();
        $past = $unix_array['past'];
        $future = $unix_array['future'];

        // ----- Build the query
        $this->core->event_db->select('id');
        if (!$this->core->options->get_option('delete_old')) {
            $this->core->event_db->where = ' WHERE `event_time` >= \'' . $past . '\' AND `event_time` <= \'' . $future . '\'';
        }
        $db_id_array = $this->core->event_db->get();

        // ----- Compare the two ID arrays and update inactive events
        foreach ($db_id_array as $db_id) {
            $id = $db_id->id;

            if (!(in_array($id, $this->event_id_array))) {
                $event = (array) $this->core->event_db->get($id, TRUE);
                $event['status'] = 'inactive';
                $d = $this->core->event_db->save($event, $event['id']);
            }
        }

        // ----- If they want inactive events deleted then delete them
        if ($this->core->options->get_option('auto_delete')) {
            $this->delete_inactive_events();
        }
    }

    /**
     * Get all inacive events and delete them
     */
    public function delete_inactive_events() {
        $this->core->event_db->select('id');
        $this->core->event_db->where('status', 'inactive');
        $inactive = $this->core->event_db->get();
        foreach ($inactive as $id) {
            $id = $id->id;
            $this->core->event_db->delete($id);
        }
    }

    /**
     * Take the integer value of months and shift the current unix time
     * to the appropriate date
     *
     * @return type
     */
    public function get_unix_for_query_options() {

        $past = $this->core->options->get_option('past_months');
        $past = nm_shift_unix(intval('-' . $past), 'month');

        $future = $this->core->options->get_option('future_months');
        $future = nm_shift_unix(intval($future), 'month');

        return array('past' => $past, 'future'=>$future);

    }

    public function clean_old_posts() {

    }

}
