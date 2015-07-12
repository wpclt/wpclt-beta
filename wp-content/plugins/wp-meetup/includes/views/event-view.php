<?php

class WPMeetupEventView {

    /**
     * Takes an single event and uses the data to create the display for the event
     * @param type $event The event object
     * @param bool $widget Whether or not this is for a widget
     * @return STRING
     */
    public static function calendar_view($event, $widget = FALSE, $simple = FALSE) {
        global $wp_meetup;
        $event_raw = unserialize($event->event);
        if ($wp_meetup->options->get_option('link_redirect')) {
            $url = $event->event_url;
        }
        else {
            $url = get_permalink($event->wp_post_id);
        }
        $output = '';
        if ($widget) {
            $output .= '<a href="' . $url . '" title="' . WPMeetupEventView::edit_name($event_raw->name) . '">';
            $output .= '<div class="wpm-single group' . $event->group_id . '">';
            //$output .= date( 'g:i A',$event->event_time);
            $output .= '<br />';
            $output .= '</div>';
            $output .= '</a>';
        } else {
            $output .= '<a href="' . $url . '">';
            $output .= '<div class="wpm-single group' . $event->group_id . '">';
            $output .= date( 'g:i A',$event->event_time) . ' - ' . WPMeetupEventView::edit_name($event_raw->name);
            $output .= '</div></a>';
        }
        return $output;
    }

    /**
     * Takes a single event and creates the appropriate list view
     * @param type $event
     * @param bool $widget
     * @return string
     */
    public static function list_view($event, $widget = FALSE, $simple = FALSE) {
        global $wp_meetup;
        $event_raw = unserialize($event->event);
        if ($wp_meetup->options->get_option('link_redirect')) {
            $url = $event->event_url;
        }
        else {
            $url = get_permalink($event->wp_post_id);
        }
        $event_raw = unserialize($event->event);
        $output = '';

        if ($widget || $simple) {
            $output .= '<div class="wpm-date-display group' . $event->group_id . '">';
            $output .= date( 'M',$event->event_time);
            $output .= '<br />';
            $output .= date( 'j',$event->event_time);
            $output .= '</div>';
            $output .= '<div class="widget-meetup-event-list-day">';
            $output .= '<a href="' . $url . '">';
            $output .= '<div class="wpm-single">';
            $output .= '<strong>' . $event_raw->group->name . '</strong> - ' . $event_raw->name;
            $output .= '</div></a></div>';
            $output .= '<div class="clear"></div>';
        } else {
            $output .= '<div class="wpm-single event-list-item">';
            $output .= '<h2>';
            $output .= '<a href="' . $url . '">';
            $output .= $event_raw->name;
            $output .= '</a>';
            $output .= '</h2>';
            $output .= '<h6>Held by '. $event_raw->group->name . ' on ' . date( 'j F Y g:i A',$event->event_time) . '</h6>';
            $output .= '<p>';
            $end = 250;
            if (strlen($event_raw->description) < $end) {$end = strlen($event_raw->description);}
            $output .= substr($event_raw->description, 0, $end) . '... ';
            $output .= '<a href="' . $url . '">';
            $output .= 'Read More';
            $output .= '</a>';
            $output .= '</p>';
            $output .= '</div>';
            $output .= '<hr>';

        }

        return $output;
    }

    public static function edit_name($event_name) {
        $new_name = '';
        $c = 0;
        $name_array = explode( " ", $event_name);
        foreach ($name_array as $word) {
            $c++;
            if (strlen($word) > 10) {
                $new_name .= substr($word, 0 , 7) . '...';
                return $new_name;
            }
            else if ($c == 5) {
                $new_name .= $word . '...';
                return $new_name;
            }
            else {
                $new_name .= $word . ' ';
            }
        }
        return $new_name;
    }
}
