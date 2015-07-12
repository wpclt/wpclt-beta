<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupCalendar {

    /**
     *
     * @var WPMeetup
     */
    var $core;

    /**
     *
     * @var ARRAY
     */
    var $atts;

    /**
     *
     * @var ARRAY
     */
    var $cal;

    /**
     * @var ARRAY
     */
    var $default_day = array('events' => array());

    /**
     *
     * @var ARRAY
     */
    var $use_events = array();

    /**
     * Since this is used for both the shortcode as the widget, this differentiates the uses.
     * @var BOOL
     */
    var $is_widget;

    public function __construct($core, $atts, $widget = false) {
        $this->core = $core;
        $this->is_widget = $widget;
        $defaults = array(
            'width' => 'one',
            'group' => '',
            'past'             => '0',
            'future'           => '0',
            'number_of_months' => '1',
            'start_month'      => '0',
            'end_month'        => '0',
            'legend' => NULL,
        );
        if (is_array($atts)) {
            $this->atts = array_merge($defaults, $atts);
        }
        else {
            $this->atts = $defaults;
        }

        if (!$this->is_widget) {
            $this->atts['legend'] = $this->core->options->get_option('legend');
        }
    }

    public function execute() {
        $output = '';

        $output .= '<div class="wp-meetup-wrapper' . $this->atts['width'] . '">';

        if ($this->atts['legend']) {
            if ($this->core->options->get_option('single_legend') && !empty($this->atts['group'])) {
                $id = $this->get_group_id();
                $this->core->group_db->select('group_name');
                $this->core->group_db->where('group_id', $id);
                $single_legend_title = $this->core->options->get_option('single_legend_title');
                $group_name = $this->core->group_db->get(NULL, TRUE, TRUE);
                $output .= '<h5>' . $single_legend_title . '</h5><h3> ' . $group_name . '</h3>';
            }
            else {
                $output .= $this->return_legend();
            }
        }
        $this->sort_events();
        $localtime = current_time('mysql');
        $strtotime = strtotime($localtime);
        $today = getdate($strtotime);
        $base_month = $today['mon'];
        $base_year = $today['year'];
        $queue_front = $base_month - $this->atts['past'] + $this->atts['start_month'];
        $queue_end = $base_month + $this->atts['future'] + $this->atts['end_month'];
        while ($queue_front <= $queue_end) {
            $month = $queue_front;
            $year = $base_year;
            if ($queue_front <= 0) {
                $month = $month + 12;
                $year = $year - 1;
            }
            else if ($queue_front > 12) {
                $month = $month - 12;
                $year = $year + 1;
            }
            $output .= $this->render_month($month, $year);
            $queue_front = $queue_front + 1;
        }
        $output .= $this->core->return_nm_credit();

        $output .= '</div>'; // Close .wp-meetup-wrapper
        $this->group_color_styles();
        return $output;

    }

    public function build_skeleton($month, $year) {
        // Calendar display
        $skeleton = array();
        $week = array();
        $firstWeekdayOfMonth = date('w', mktime(0, 0, 0, $month, 0, $year));
        $daysInMonth = date('d', mktime(0, 0, 0, $month+1, 0, $year));
        if ($firstWeekdayOfMonth != 6) {
            for ($i=0; $i<$firstWeekdayOfMonth+1; $i++) {
                $day = '0';
                $week[] = $day;
            }
        }
        for ($i=1; $i<=$daysInMonth; $i++) {
            $day = $i;
            $week[] = $day;
            if (count($week)>=7) {
                $skeleton[] =$week;
                $week    = array();
            }
        }
        if ($week != array()) {
            $skeleton[] = $week;
        }
        return $skeleton;
    }

    public function sort_events() {
        $this->filter_groups();
        foreach ($this->use_events as $event) {
            $time = $event->event_time;
            $year = date('Y', $time);
            $month = date('n', $time);
            $day = date('j', $time);
            $this->store_event($year, $month, $day, $event);
        }
    }

    private function filter_groups() {
        $id = $this->get_group_id();
        if (!is_null($id)) {
            foreach ($this->core->events as $event) {
                if ($event->group_id == $id) {
                    $this->use_events[] = $event;
                }
            }
        }
        else {
            $this->use_events = $this->core->events;
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

    private function store_event($year, $month, $day, $event) {
        if (!isset($this->cal[$year])) {
            $this->cal[$year] = array();
        }
        if (!isset($this->cal[$year][$month])) {
            $this->cal[$year][$month] = array();
        }
        if (!isset($this->cal[$year][$month][$day])) {
            $this->cal[$year][$month][$day] = $this->default_day;
        }
        $this->cal[$year][$month][$day]['events'][] = WPMeetupEventView::calendar_view($event, $this->is_widget);
    }

    public function get_day($year, $month, $day) {

        if (isset($this->cal[$year]) && isset($this->cal[$year][$month]) && isset($this->cal[$year][$month][$day])) {
            return $this->cal[$year][$month][$day];
        }
        else {
            return $this->default_day;
        }
    }

    public function render_month($month, $year) {
        $skeleton = $this->build_skeleton($month, $year);
        return $this->build_calendar($skeleton, $month, $year);
    }

    public function build_calendar($skeleton, $month, $year) {
        $date = strtotime($year . '-' . $month);
        $date = date("F Y",$date);

        $output = '';

        $output .= '<div class="wp-meetup-calendar ';
        $output .= $this->atts['width'];
        $output .= '">';
        $output .= '<h4 class="wpm-current-date-display">';
        $output .= $date;
        $output .= '</h4>';
        $output .= '<table class="table calendar-month heading-date">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>Sun</th>';
        $output .= '<th>Mon</th>';
        $output .= '<th>Tue</th>';
        $output .= '<th>Wed</th>';
        $output .= '<th>Thu</th>';
        $output .= '<th>Fri</th>';
        $output .= '<th>Sat</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        foreach ($skeleton as $week) {
            $output .= '<tr class="calendar-week">';
            foreach ($week as $day) {
                $output .= '<td class="wpm-table-data wpm-day">';
                $day_data = $this->get_day($year, $month, $day);
                $output .='<div class="wpm-calendar-entry wpm-date">';
                $output .='<div class="wpm-number-display">';
                if (!($day == '0')) {
                    $output .= $day;
                }
                $output .= '</div>';
                $output .= '<div class="wpm-event-list">';
                foreach ($day_data['events'] as $event) {
                    $output .= $event;
                }
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</td>';
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>';
        return $output;
    }

    public function render_calendar($skeleton, $month, $year) {
        $date = strtotime($year . '-' . $month);
        $date = date("F Y",$date);
        ?>
<div class="wp-meetup-calendar <?php echo $this->atts['width'] ?>">
    <h4 class="wpm-current-date-display">
        <?php echo $date ?>
    </h4>
    <table class="table calendar-month heading-date">
        <thead>
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($skeleton as $week) {
                ?>
                <tr class="calendar-week">
                <?php
                foreach ($week as $day) {
                    ?>
                    <td class="wpm-table-data wpm-day">
                    <?php

                    $day_data = $this->get_day($year, $month, $day);
                    ?>
                        <div class="wpm-calendar-entry wpm-date">
                            <div class="wpm-number-display">
                                <?php if (!($day == '0')) {echo $day;} ?>
                            </div>
                            <div class="wpm-event-list">
                            <?php foreach ($day_data['events'] as $event) { echo $event;} ?>
                            </div>
                        </div>
                    </td>
                    <?php
                }
                ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
        <?php
        $this->group_color_styles();

    }

    private function group_color_styles() {

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
            <?php
        }
        ?> </style> <?php
    }

    private function render_legend() {
        if ($this->is_widget) {
            ?>
            <div class="wpm-calendar-legend">
                <div class="wpm-legend-title wpm-widget-legend-title wpm-legend-item"> <?php echo __($this->core->options->get_option('legend_title')) ?> </div>
                <div class="clear"></div>
                <?php
                foreach ($this->core->groups as $group) {
                    ?>
                <div class="wpm-legend-item group<?php echo $group->group_id ?>" title="<?php echo __($group->group_name) ?>"><br /></div>
                    <?php
                }
                ?>
            </div>
            <div class="clear"></div>
            <?php
        }
        else {
            ?>
            <div class="wpm-calendar-legend">
                <div class="wpm-legend-item wpm-legend-title"><?php echo __($this->core->options->get_option('legend_title')) ?></div>
                <div class="clear"></div>
                <?php
                $count = 0;
                foreach ($this->core->groups as $group) {
                    ?>
                    <div class="wpm-legend-item group<?php echo $group->group_id ?>"><?php echo __($group->group_name) ?></div>
                    <?php
                    $count = $count + 1;
                    if ($count >= 3) {
                        $count = 0;
                        ?>
                            <div class="clear"></div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="clear"></div>
            <?php
        }
    }

    private function return_legend() {
        $output ='';

        if ($this->is_widget) {

            $output .= '<div class="wpm-calendar-legend">';
            $output .= '<div class="wpm-legend-title wpm-widget-legend-title wpm-legend-item"> ';
            $output .= __($this->core->options->get_option('legend_title'));
            $output .= '</div>';
            $output .= '<div class="clear"></div>';


            foreach ($this->core->groups as $group) {

                $output .= '<div class="wpm-legend-item group';
                $output .= $group->group_id;
                $output .= '" title="';
                $output .= __($group->group_name) ;
                $output .= '"><br /></div>';
            }

            $output .= '</div>';
            $output .= '<div class="clear"></div>';

        }

        else {

            $output .= '<div class="wpm-calendar-legend">';
            $output .= '<div class="wpm-legend-item wpm-legend-title">';
            $output .=  __($this->core->options->get_option('legend_title'));
            $output .= '</div>';
            $output .= '<div class="clear"></div>';


            $count = 0;
            foreach ($this->core->groups as $group) {

                $output .= '<div class="wpm-legend-item group';
                $output .= $group->group_id;
                $output .= '">';
                $output .= __($group->group_name);
                $output .= '</div>';


                $count = $count + 1;
                if ($count >= 3) {
                    $count = 0;

                    $output .= '<div class="clear"></div>';


                }
            }

            $output .= '</div>';
            $output .= '<div class="clear"></div>';

        }
        return $output;
    }
}
