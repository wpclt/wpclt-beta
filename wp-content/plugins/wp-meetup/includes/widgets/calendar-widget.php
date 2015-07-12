<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupCalendarWidget extends NMWidget {

    /**
     *
     * @var String 
     */
    var $slug = 'meetup_calendar';
    
    var $name = 'Meetup Calendar';
    
    var $classname = 'meetup_calendar';
    
    var $description = 'A calendar that displays events from Meetup.com'; 
    
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
            'legend' => $instance['widget_legend'],
        );
        $wp_meetup->shortcode_calendar($atts, TRUE);
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
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'widget_legend' => FALSE ) );
        $title = strip_tags($instance['title']);
        $legend = strip_tags($instance['widget_legend']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('widget_legend'); ?>"><?php _e('Display Legend:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('widget_legend'); ?>" name="<?php echo $this->get_field_name('widget_legend'); ?>" type="checkbox" value="checked"<?php echo esc_attr($legend); ?> /></p>
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
        $instance['widget_legend'] = strip_tags($new_instance['widget_legend']);

        return $instance;
    }

}