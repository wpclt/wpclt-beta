<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 * 2014-04-18
 *      - Imported the functions from previous version
 * 2014-04-21
 *      - Added Extention of WPMAdminPage
 */

class WPMeetupMainAdmin  extends WPMeetupAdminPage{

    /**
     *
     * @var WPMeetup
     */
    var $core;

    public function __construct($core) {
        $this->core = $core;
        add_menu_page(
            'WP Meetup Settings',
            'WP Meetup',
            'administrator',
            'wp_meetup_settings',
            array($this, 'create_page')
        );
    }

    public function display_page() {

        $this->render_postbox_open('Instructions');
        $this->insert_instructions();
        $this->render_postbox_close();

        $this->render_postbox_open('Calendar Shortcode');
        $this->insert_calendar_shortcode();
        $this->render_postbox_close();

        $this->render_postbox_open('Event List Shortcode');
        $this->insert_eventlist_shortcode();
        $this->render_postbox_close();

        $this->render_postbox_open('Update Events');
        $this->insert_event_update();
        $this->render_postbox_close();
    }

    private function insert_instructions() {
        ?>
            <p>Thanks for using the WP Meetup Plugin for WordPress!</p>
            <p>With the release of version 2.2.0, we are introducing a new shortcode as well as a new admin dashboard. We also bettered the functionality of the widgets and added the ability to customize them more.</p>
            <br />
        <?php
    }
    private function insert_event_update() {
        ?>
            <p>The below button will force the WP Meetup plugin to update the database of events. This way if you just posted an event on Meetup and want the event on the calendar right now, all thats needed is one click. </p>
            <div class="demand-update-button">
                    <form method="POST" action="">
                    <input type="hidden" name="update" value="wpm-update-events" />
                    <input type="submit" value="Update Events Now" class="button">
                    </form>
            </div>
        <?php

    }

    private function insert_calendar_shortcode() {
        ?>
            <p>In order to display the calendar on a page, place <strong>[meetup-calendar]</strong> on the page. By default, this displays the current month.</p>
            <p>Various parameters are allowed for this shortcode.</p>
            <?php $this->render_postbox_open('Calendar Parameters'); ?>
            <ul>
                <li>
                    <p><strong>past:</strong> The number of past months that you would like displayed.</p>
                    <p>Ex: <strong>past="2"</strong></p>
                </li>
                <li>
                    <p><strong>future:</strong> The number of future months that you would like displayed, not including the current month.</p>
                    <p>Ex: <strong>future="1"</strong></p>
                </li>
                <li>
                    <p><strong>group:</strong> If you would only like a single group's events displayed on this page then you can specify that group here.</p>
                    <p>Ex: <strong>group="group_urlname"</strong></p>
                </li>
                <li>
                    <p><strong>width:</strong> Defines the width of the calendar. IMPORTANT --- Depending on the font-size of your site, this parameter might not work. For example, if you have a large font-size and try to create a one-third calendar, then the calendar will expand outside of the meant space. <strong>We are still working on this parameter.</strong></p>
                    <p>Ex: <strong>width="one-half"</strong></p>
                </li>
            </ul>
            <?php $this->render_postbox_close(); ?>
            <p>An example of all parameters in use would be as follows: <strong>[meetup-calendar past="2" future="1" group="group_urlname" width="one-third"]</strong></p>
        <?php
    }

    private function insert_eventlist_shortcode() {
        ?>
            <p>In order to display the event list on a page, place <strong>[meetup-list]</strong> on the page. By default, this displays all events, past and future. </p>
            <p>Various parameters are allowed for this shortcode.</p>
            <?php $this->render_postbox_open('Event List Parameters'); ?>
            <ul>
                <li>
                    <p><strong>max:</strong> The maximum number of events to be listed.</p>
                    <p>Ex: <strong>max="10"</strong></p>
                </li>
                <li>
                    <p><strong>show:</strong> This parameter currently only have one option. That would be to display only the upcoming events instead of the default which is both past and future.</p>
                    <p>Ex: <strong>show="future"</strong></p>
                </li>
                <li>
                    <p><strong>group:</strong> If you would only like a single group's events displayed on this page then you can specify that group here.</p>
                    <p>Ex :<strong>group="group_urlname"</strong></p>
                </li>
                 <li>
                    <p><strong>display:</strong> This option alters the way in which the event list will be rendered. "simple" will mimmic the rendering of the event-list widget, and "smaller" will render the event-list as usual, however with smaller text.</p>
                    <p>Ex :<strong>display="simple"</strong></p>
                    <p>Ex :<strong>display="smaller"</strong></p>
                </li>
                <li>
                    <p><strong>width:</strong> This where you can sepecify the % of the content area for the list to occupy. (default = 100%)</p>
                    <p>Ex :<strong>width="30%"</strong></p>
                </li>
                <li>
                    <p><strong>align:</strong> If the list is displayed less than full width, as specified with the above parameter, this will float the list to either side of the content area, and other content can flow arround the list. If "center" is used, content will not flow around the list. (options = "left","right","center")</p>
                    <p>Ex :<strong>align="left"</strong></p>
                </li>
            </ul>
            <?php $this->render_postbox_close(); ?>
            <p>An example of all parameters in use would be as follows: <strong>[meetup-list max="5" show="future" group="group_urlname" simple="true"]</strong></p>
        <?php
    }
}
