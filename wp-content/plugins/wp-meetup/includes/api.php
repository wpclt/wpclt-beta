<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-17
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 * 2014-4-17
 *      - Introduced the get_results() method
 */

class WPMeetupAPI extends ApiInteraction {
    
    var $key;
    
    /**
     *
     * @var WPMeetup
     */
    var $core;
    
    /**
     * 
     * @param WPMeetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        $this->key = $core->options->get_option('key');
    }

    public function get_results($parameters = array(), $category = 'events', $type = 'get') {
        if (!$this->key) {
            $this->key = $this->core->options->get_option('key');
        }
        if (is_string($parameters)) {
            $past = $this->core->options->get_option('past_months');
            $future = $this->core->options->get_option('future_months');
            $max = $this->core->options->get_option('max_events');
            $parameters = array(
                'group_urlname' => $parameters,
                'time' => '-' . $past . 'm,' . $future . 'm',
                'page' => $max,
            );
        }
        if($type === 'post') {
            $this->post_results($parameters);
        }
        
        if ($category === 'events') {
            /**
             * Set defaults and merge parameters
             */
            $defaults = array(
                'status'=>'past,upcoming',
                'time' => '-1m,3m',
                'page' => '100',
                'group_urlname' =>'',
            );
            $settings = array_merge($defaults, $parameters);
            $url = 'https://api.meetup.com/2/events.json?key=';

            $url .= $this->key;
            
            foreach ($settings as $key=>$value) {
                $url .= '&' . $key . '=' . $value; 
            }
        }
        else if ($category == 'groups'){
             /**
             * Set defaults and merge parameters
             */
            $defaults = array(
                'group_urlname'=>'',
            );
            $settings = array_merge($defaults, $parameters);

            $url = 'https://api.meetup.com/2/groups.json?key=';

            $url .= $this->key;
            
            foreach ($settings as $key=>$value) {
                $url .= '&' . $key . '=' . $value; 
            }
        }
        $body = $this->remote_get($url);
        $body = json_decode($body);
        return $body;
    }
    
    private function post_results($parameters = array(), $category = 'events') {
        if (isset($parameters['id'])) {
            $url = $this->post_update_event($parameters);
        }
        else {
            $url = $this->post_new_event($parameters); 
        }
        $body = $this->remote_post($url);
    }
    
    private function post_new_event($parameter) {
        
        if (isset($parameters['group_id']) && isset($parameters['group_urlname']) && isset($parameters['name'])) {
            $url = 'https://api.meetup.com/2/events/.json?key=';
            $url .= $this->key;
            foreach ($parameters as $key=>$value) {
                 $url .= '&' . $key . '=' . $value;
            } 
        }
        else {
            echo '<div class="error">' . __('When attempting to add a new event, the parameter array must include group_id, group_urlname, and name for the event.') . '</div>';
            $url = '';
        }
        return $url;
    }
    
    private function post_update_event($parameter) {
        $url = 'https://api.meetup.com/2/events/:'. $parameter['id'] . '.json?key=';
        $url .= $this->key;
        foreach ($parameters as $key=>$value) {
            $url .= '&' . $key . '=' . $value;
        }
    } 
}