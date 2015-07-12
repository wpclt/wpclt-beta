<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupTrigger {

    /**
     *
     * @var BOOL
     */
    var $update = FALSE;

    /**
     *
     * @var WPMeetup
     */
    var $core;

    public function __construct($core) {
        $this->core = $core;
        if ( ! wp_next_scheduled( 'wpm-event-update' ) ) {
            wp_schedule_event( time(), 'daily', 'wpm-event-update' );
        }
        add_action( 'wpm-event-update', array(&$this, 'execute_update') );

        /**
         * Debug Test Cron Job Query
         */
        //do_action('wpm-event-update');

    }

    public function execute_update() {
        $error = $this->update_events();
        if ($error) {
            if (is_admin()) {
                echo '<div class="error">' . __('WP Meetup: Query was cancelled. Please try again at a later time.') . '</div>';
            }
            return;
        }
        $this->cleanse_old_events();
        $this->update_posts();
    }

    public function update_events() {
        $error = $this->core->factory->query();
        if ($error) {
            return $error;
        }
        else {
            return null;
        }
    }

    public function cleanse_old_events() {
        $this->core->factory->filter_old_events();
        //$this->core->factory->clean_old_posts();
    }

    public function update_posts() {
        $this->core->factory->update_posts();

    }

}
