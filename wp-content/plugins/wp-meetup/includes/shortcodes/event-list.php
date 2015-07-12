<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupEventList {

    /**
     *
     * @var WPMeetup
     */
    var $core;

    /**
     *
     * @var array
     */
    var $atts;

    /**
     * Since this is used for both the shortcode as the widget, this differentiates the uses.
     * @var BOOL
     */
    var $is_widget;

    /**
     *
     * @param WPMeetup $core
     * @param ARRAY $atts
     */
    public function __construct($core, $atts, $widget = FALSE) {
        $this->core = $core;
        $this->use_events = array();
        $this->is_widget = $widget;
        $defaults = array(
            'max' => NULL,
            'show' => NULL,
            'group' => '',
            'display' => '',
            'width' => '100%',
            'align' => 'center'
        );
        if (is_array($atts)) {
            $this->atts = array_merge($defaults, $atts);
        } else {
            $this->atts = $defaults;
        }
    }

    public function execute() {
        $output = '';
        $simple = FALSE;
        $width = $this->atts['width'];
        $align = $this->atts['align'];
        $display = $this->atts['display'];
        if( $display == 'simple') {
            $simple = TRUE;
        }
        if( $width != '100%') {
            $output .= '<div style="width:'.$width .';';
            if( $align == 'center') {
                $output .= '" class="aligncenter';
            } else {
                $output .= 'float:'. $align . ';';
            }
            $output .= '">';
        }
        if ($this->is_widget || $simple) {
            $output .= '<div class="meetup-widget-event-list">';
        }
        $use_events = array();

        // The user only wants future events shown
        if ($this->atts['show'] == 'future') {
            foreach ($this->core->events as $event) {
                if ($event->event_time > date('U')) {
                    $use_events[] = $event;
                }
            }
        }
        // User doesn't limit the show attribute
        else {
            foreach ($this->core->events as $event) {
                $use_events[] = $event;
            }
        }

        $this->filter_groups($use_events);

        // Check for a count limit, if no limit exists
        if (is_null($this->atts['max'])) {
            foreach ($this->use_events as $event) {
                $output .= WPMeetupEventView::list_view($event, $this->is_widget, $simple);
            }
        }
        // Count limit exists, execute while loop
        else {
            $i = 0;
            $c = 0;
            while ($c < $this->atts['max']) {
                if (isset($this->use_events[$i])) {
                    $output .= WPMeetupEventView::list_view($this->use_events[$i], $this->is_widget, $simple);
                }
                $c++;
                $i++;
            }
        }
        if ($this->is_widget || $simple) {
            $output .= '</div>';
        }

        if($width != '100%') {
            $output .=  '</div>';
        }
        if($this->atts['display'] == 'smaller') {
            $output .= ' <style>
                            .event-list-item {
                              font-size:60%;
                              margin:0;
                            }
                            .event-list-item>h2 {
                              font-size:20px;
                              margin-top:0.5em;
                              margin-bottom:1em;
                            }
                            .event-list-item>h6 {
                              font-size:15px;
                              margin-top:0.5em;
                              margin-bottom:1em;

                            }
                        </style>';
        }


        $this->group_color_styles();
        $output .= $this->core->return_nm_credit();
        return $output;
    }

    private function filter_groups($events) {
        $id = $this->get_group_id();
        if (!is_null($id)) {
            foreach ($events as $event) {
                if ($event->group_id == $id) {
                    $this->use_events[] = $event;
                }
            }
        }
        else {
            $this->use_events = $events;
        }
    }

    private function get_group_id() {
        $group = $this->atts['group'];
        $id = NULL;
        if (!empty($this->atts['group'])) {
            $group = $this->atts['group'];
            $this->core->group_db->select('group_id');
            $this->core->group_db->where(array('group_name' => $group));
            $id = $this->core->group_db->get(NULL, TRUE, TRUE);
            if (empty($id)) {
                $this->core->group_db->select('group_id');
                $this->core->group_db->where(array('group_slug' => $group));
                $id = $this->core->group_db->get(NULL, TRUE, TRUE);
                if (empty($id)) {
                    $this->core->group_db->select('group_name');
                    $this->core->group_db->where(array('group_id' => $group));
                    $id = $this->core->group_db->get(NULL, TRUE, TRUE);
                    if (!empty($id)) {
                        $this->atts['group'] = $id;
                        $id = $this->get_group_id();
                    }
                    if (empty($id)) {
                        if (is_user_logged_in()) {
                            echo '<div class="error">' . _('Improper group used in shortcode.</div>');
                        }
                    }
                }
            }
        }
        return $id;
    }

    public function group_color_styles() {

        ?> <style> <?php
        foreach ($this->core->groups as $group) {
            ?>
.group<?php echo $group->group_id;?> {
    background-color: <?php echo $group->color; ?>;
}
            <?php
        }
        if ($this->core->options->get_option('link_color')) {
            ?>
.wp-meetup-calendar a {
    color: #ffffff !important;
}

.wpm-legend-item {
    color: #ffffff !important;
}

.wpm-date-display {
    color: #ffffff !important;
}
            <?php
        }
        ?> </style> <?php
    }

}
