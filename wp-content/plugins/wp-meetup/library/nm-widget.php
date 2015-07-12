<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * v0.0.1 - 2014-04-11
 *      - Initial Class Creation
 */

class NMWidget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        if (is_null($this->name) || is_null($this->classname) || is_null($this->description) || is_null($this->slug)) {
            echo '<div class="error">' . __('Atleast one of the four widget variables was not set.') . '</div>';
        }
        $widget_ops = array(
            'classname' => __($this->classname),
            'description' => __($this->description),
        );
        parent::__construct($this->slug, __($this->name), $widget_ops);
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
        echo '<div class="error">' . __('The function widget() was not overwritten in the Widget Class.') . '</div>';
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        echo '<div class="error">' . __('The function form() was not overwritten in the Widget Class.') . '</div>';
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
        echo '<div class="error">' . __('The function update() was not overwritten in the Widget Class.') . '</div>';

        return $instance;
    }

}