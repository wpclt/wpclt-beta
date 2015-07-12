<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupAdmin {

    /**
     *
     * @var WPMeetup
     */
    var $core;

    /**
     *
     * @var array
     */
    var $page_array;

    /**
     *
     * @param WPMeetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        $this->build_page_array();
        add_action('admin_menu', array( &$this, 'add_admin_pages' ));
        add_action('admin_enqueue_scripts', array(&$this, 'load_settings_styles'), 100);
        add_action('all_admin_notices', array(&$this, 'prompt_support'));
    }

    /**
     * Creates each new admin page.
     */
    public function add_admin_pages() {
        new WPMeetupMainAdmin($this->core);
        new WPMeetupOptionsAdmin($this->core);
        new WPMeetupGroupsAdmin($this->core);
        new WPMeetupEventsAdmin($this->core);
        new WPMeetupDebugAdmin($this->core);
    }

    public function build_page_array() {

    }

    public function load_settings_styles() {
        $pluginDirectory = trailingslashit(plugins_url(basename(dirname(__FILE__))));
        wp_register_style('wpm-admin-styles', $pluginDirectory . 'css/admin-styles.css');
        wp_enqueue_style('wpm-admin-styles');
        wp_register_script('wpm-admin-script', $pluginDirectory . 'js/coffee/nm-dashboard-script.js');
        wp_enqueue_script('wpm-admin-script');
    }

    public function prompt_support() {
        if (!$this->core->options->get_option('support') && intval($this->core->options->get_option('queue_prompt')) != 1 && intval($this->core->options->get_option('queue_prompt')) < time()) {
            ?>
                <div class="nm-support-prompt nm-error">
                    <div class="nm-support-staff-prompt-exit">
                        <form action="" method="post">
                            <input type="hidden" name="update" value="wpm-update-prompt" />
                            <!-- <input type="hidden" name="queue_prompt" value="<?php  //echo time() + 10; ?>" /> -->
                            <input type="hidden" name="queue_prompt" value="<?php  //echo time() + 2419200; ?>1" />
                            <input type="hidden" name="install_count" value="<?php  echo $this->core->options->get_option('install_count') + 28; ?>" />
                            <input type="submit" title="Close this message" value="X">
                        </form>
                    </div>
                    <div class="nm-support-staff-form">
                        <form action="" method="post">
                            <input type="hidden" name="update" value="wpm-update-support-prompt" />
                            <input type="hidden" name="support" value="checked" />
                            <div class="nm-support-staff-label nm-support-staff-prompt-label">
                                <label>It has been over <?php echo $this->core->options->get_option('install_count'); ?> days, how are you liking WP Meetup? Please consider <a href="http://wordpress.org/extend/plugins/wp-meetup/">reviewing</a> the plugin or adding a small link to the bottom of your calendar page so we can spend more time building this plugin!</label>
                            </div>
                            <input type="submit" class="nm-support-staff-prompt-submit" value="Help Improve WP Meetup" />
                            <div class="clear"></div>
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>

            <?php
        }
    }

}
