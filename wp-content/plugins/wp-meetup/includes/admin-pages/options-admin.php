<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 * 2014-04-21
 *      - Added Extention of WPMAdminPage
 */

class WPMeetupOptionsAdmin extends WPMeetupAdminPage{

    /**
     *
     * @var WPMeetup 
     */
    var $core;
    
    public function __construct($core) {
        $this->core = $core;
        add_submenu_page(
            'wp_meetup_settings',
            'Options',
            'Options',
            'administrator',
            'wp_meetup_options',
            array($this, 'create_page')
        );
    }
    
    public function display_page() {
        $options = $this->core->options->get_option();
        ?>
            <form action="" method="post">
                <input type="hidden" name="update" value="wpm-update-options">
                <?php $this->render_postbox_open('General Options'); ?>
                <input type="checkbox" name="link_color" value="checked" <?php echo $options['link_color'] ?> />
                <label>Checking this box will make all links within the calendar white instead of the color created by your theme default.</label><br />
                <input type="checkbox" name="include_homepage" value="checked" <?php echo $options['include_homepage'] ?> />
                <label>Would you like Events to appear on your homepage?</label>
                <br />
                <input type="checkbox" name="link_redirect" value="checked" <?php echo $options['link_redirect'] ?> />
                <label>Checking this box will make all links within the calendar and widgets direct users to the Meetup.com event page.</label><br />
                <input type="checkbox" name="support" value="checked"<?php echo $options['support'] ?> />
                <label>Support the development team!</label>
                <br /> 
                <input type="checkbox" name="venue" value="checked" <?php echo $options['venue'] ?> />
                <label>Would you like the venue address to display on event posts?</label>
                <br />
                <label>Root for custom post type: </label>
                <input type="text" name="wpm_pt" value="<?php echo $options['wpm_pt'] ?>" />   
                <br />
                <label>Link name for event posts: </label>
                <input type="text" name="link_name" value="<?php echo $options['link_name'] ?>" />   
                <br />
                
                <input type="submit" class="button" value="Update Options" />
                <?php 
                $this->render_postbox_close();
                $this->render_postbox_open('Legend Options');
                ?>
                <input type="checkbox" name="legend" value="checked" <?php echo $options['legend'] ?> />
                <label>Would you like to display a legend showing which color applies to which group?</label>
                <br />
                <input type="checkbox" name="single_legend" value="checked" <?php echo $options['single_legend'] ?> />
                <label>On single-group calendars, do you only want one group displayed?</label>
                <br />
                <label>Title for calendar legend: </label>
                <input type="text" name="legend_title" value="<?php echo $options['legend_title'] ?>" />   
                <br />
                <label>Using the single-group legend? Add text above the group name: </label>
                <input type="text" name="single_legend_title" value="<?php echo $options['single_legend_title'] ?>" />   
                <br />
                
                <input type="submit" class="button" value="Update Options" />
                <?php
                $this->render_postbox_close();
                $this->render_postbox_open('Database Options');
                ?>
                <input type="checkbox" name="auto_delete" value="checked" <?php echo $options['auto_delete'] ?> />
                <label>Would you like to automatically delete inactive events?</label>
                <br />
                <input type="checkbox" name="delete_old" value="checked" <?php echo $options['delete_old'] ?> />
                <label>Would you like events outside of your Query range to be marked inactive, not displaying on calendars and in archive pages?</label>
                <br />
                
                <input type="submit" class="button" value="Update Options" />
                <?php
                $this->render_postbox_close();
                $this->render_postbox_open('Query Options'); 
                ?>
                <label>Number of past months queried: </label>
                <input type="number" name="past_months" value="<?php echo $options['past_months'] ?>">
                <br />
                <label>Number of future months queried: </label>
                <input type="number" name="future_months" value="<?php echo $options['future_months'] ?>">
                <br />
                <label>Max number of Events pulled per group:</label>
                <input type="number" name="max_events" value="<?php echo $options['max_events'] ?>">
                <br /> 
                <input type="submit" class="button" value="Update Options" />
                <?php $this->render_postbox_close(); ?>
            </form>
        <?php 

    }
    
    
}