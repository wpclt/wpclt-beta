<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupEventListWidget extends NMWidget {

    var $slug = 'meetup_event_list';
    
    var $name = 'Meetup Event List';
    
    var $classname = 'meetup_event_list';
    
    var $description = 'A list on Meetup.com events.';
    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        global $wp_meetup;
        extract($args);
        echo $before_widget;
        if ( $instance['title'] ) {
            echo $before_title . $instance['title'] . $after_title;
        }
        $atts = array(
            'max' => $instance['max_events'],
            'show' => 'future',
        );
        $wp_meetup->shortcode_list($atts, TRUE);
        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'max_events' => 3 ) );
        $title = strip_tags($instance['title']);
        $max_events = strip_tags($instance['max_events']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('max_events'); ?>"><?php _e('Maximun events shown:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('max_events'); ?>" name="<?php echo $this->get_field_name('max_events'); ?>" type="number" value="<?php echo esc_attr($max_events); ?>" /></p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['max_events'] = strip_tags($new_instance['max_events']);
        return $instance;
    }
}