<?php

/**
 * Created: 2014-04-21
 * Last Revised: 2014-04-21
 *
 * CHANGELOG:
 * 2014-04-21
 *      - Initial Class Creation
 */

class WPMeetupAdminPage {

    var $title = '';

     public function create_page() {
        if (!current_user_can('manage_options'))  {
                wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        $key = $this->core->options->get_option('key');
        if (empty($key)) {
                // if there is no access token set, then this is run, requesting information required to generate accesss token.
                $this->request_apikey();
                echo '<div class="error">No API key stored.</div>';
        }
        if (!empty($key)) {
            $this->render_header($this->title);
            $this->render_tabs();
            $this->core->options->update_messages();
            $this->render_container_open('three-fifths');
            $this->display_page();
            $this->render_container_close();
            $this->render_container_open('two-fifths');
            $this->render_sidebar();
            $this->render_container_close();
        }
    }

    public function request_apikey() {

        ?>
        <form method="post" action="">
            <div class="wpm-settings">
                <div class="wpm-settings-header">
                    <h2>WP Meetup Settings</h2>
                    <p>Thank you for choosing to use the WP Meetup plugin for Wordpress. Please fill out the following before continuing. </p>
                    <p>For those who aren't familiar with API keys, <a href="http://www.meetup.com/meetup_api/key/" target="_blank"><strong>This link</strong></a> will take you to where you can find yours.</p>
                </div>
                <input type="hidden" name="update" value="wpm-update-options" />
                <table>
                    <tr class="wpm-register-settings-option">
                        <td><label><strong>Meetup API Key</strong></label></td>
                        <td><input name="key" type="text" autocomplete="on" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Submit" class="button" /></td>
                    </tr>
                </table>
            </div>
        </form>
        <?php
    }

    public function display_option($array) {

    }

    /**
     * Echoes the tabs at the top of each admin page
     */
    public function display_admin_tabs() {
        $pt_root = $this->core->post_type;
        $page_array = array(
            'settings' => 'Home',
            'options' => 'Options',
            'groups' => 'Groups',
            $pt_root => ucfirst($pt_root),
            'debug' => 'Debug Information',
        );
        $output = '<div class="tabs-container">';

        foreach ($page_array as $key=>$value) {
            $output .= '<div class="tab">' . '<a href="'
                    . admin_url('admin.php?page=wp_meetup_' . $key)
                    . '">'
                    . $value
                    . '</a>' . '</div>';
        }
        $output .= '</div>';

        echo $output;

    }


    /**
     * Echoes the admin header
     */
    public function display_admin_header() {
        $output = '<div class="logo-wrap one">';
        $output .= '<a href="http://www.nuancedmedia.com" title="Nuanced Media"><img src="'. plugins_url() . '/wp-meetup/images/meetup_logo.png" width="106" height="59"/></a>';
        $output .= '<h2>WP Meetup Admin</h2>';
        $output .= '</div>';
        echo $output;
    }

    function insert_review_us() {
        ?>
            <div class="review-us">
            <p>Tell us your opinion of the plugin. We are continuously working to improve your experience with the Meetup Plugin and we can do that better if we know what you like and dislike. Let us know on the Wordpress <a href="http://wordpress.org/support/view/plugin-reviews/wp-meetup">Review Page</a>. </p>
            </div>
        <?php
    }

    function insert_mailing_list() {
        ?>
        <div class="mailing-list">
        <p>Stay updated on new releases and future features for the WP Meetup Plugin by joining the email list below.</p>
        <div class="meetup-mailing-list-form">
                <script>
                jQuery(function(){
                });
                </script>
                <form method="POST" action="http://nuancedmedia.com/wordpress-meetup-plugin/">
                        <table>
                                <tr>
                                        <td>
                                                Email:
                                        </td>
                                        <td>
                                                <input name="input_2" id="input_10_2" type="text" value="" class="button" tabindex="1">
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <input type="submit" id="gform_submit_button_10" class="button gform_button" value="Join" tabindex="2" onclick="if(window[&quot;gf_submitting_10&quot;]){return false;}  window[&quot;gf_submitting_10&quot;]=true; ">
                                                <input type="hidden" class="gform_hidden" name="is_submit_10" value="1">
                                                <input type="hidden" class="gform_hidden" name="gform_submit" value="10">
                                                <input type="hidden" class="gform_hidden" name="gform_unique_id" value="">
                                                <input type="hidden" class="gform_hidden" name="state_10" value="WyJhOjA6e30iLCI3MzgxZDc3NTA3OTk0MDMwMTI4MTM4ZDczZTU1MzNkMSJd">
                                                <input type="hidden" class="gform_hidden" name="gform_target_page_number_10" id="gform_target_page_number_10" value="0">
                                                <input type="hidden" class="gform_hidden" name="gform_source_page_number_10" id="gform_source_page_number_10" value="1">
                                                <input type="hidden" name="gform_field_values" value="">
                                        </td>
                                </tr>
                        </table>
                </form>
        </div>
        </div>
        <?php
    }

    public function insert_support_box() {
        ?>
<div class="nm-support-box">
    <form method="post" action="">
        <input type="hidden" name="update" value="wpm-update-support" />
        <div class="nm-support-staff-checkbox">
            <input type="checkbox" name="support" value="checked" <?php echo $this->core->options->get_option('support'); ?> />
        </div>
        <div class="nm-support-staff-label">
            <label for="support">We thank you for choosing to use our plugin! We would also appreciate it if you allowed us to put our name on the plugin we worked so hard to build. If you are okay with us having a credit line on the calendar, then please check this box and change your permission settings.</label>
        </div>
        <br />
        <input type="submit" class="nm-support-staff-submit button" value="Change Permission Setting" />
    </form>
</div>
        <?php
    }

    /**
     * Ian's Status class brought into my parent class
     */

    public function render_header($title = NULL, $echo = TRUE) {
        global $wpm_core;
        $output = '';
        if ($title == NULL) {
            $plugin_data = get_plugin_data( $wpm_core);
            $output .= '<h1>' . $plugin_data['Name'] . '</h1>';
        } else {
            $output = '<h1>' . $title . '</h1>';
        }
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function render_sidebar() {
        if (!$this->core->options->get_option('support')) {
            $this->render_postbox_open('Support the Staff');
            $this->insert_support_box();
            $this->render_postbox_close();
        }

        $this->render_postbox_open('Need Help?');
        $this->insert_website_link();
        $this->render_postbox_close();

        $this->render_postbox_open('Review Our Plugin');
        $this->insert_review_us();
        $this->render_postbox_close();

        $this->render_postbox_open('Join Our Email List');
        $this->insert_mailing_list();
        $this->render_postbox_close();



        $this->render_postbox_open('Nuanced Media');
        $this->render_nm_logos();
        $this->render_postbox_close();
    }



    public function render_tabs($echo = TRUE) {
        /*
         * key value pairs of the form:
         * 'admin_page_slug' => 'Tab Label'
         * where admin_page_slug is from
         * the add_menu_page or add_submenu_page
         */
        $tabs = array(
            'wp_meetup_settings' => 'Dashboard',
            'wp_meetup_options' => 'Options',
            'wp_meetup_groups' => 'Groups',
            'wp_meetup_events' => 'Events',
            'wp_meetup_debug' => 'Debug',
        );

        // what page did we request?
        $current_slug = '';
        if (isset($_GET['page'])) {
            $current_slug = $_GET['page'];
        }

        // render all the tabs
        $output = '';
        $output .= '<div class="tabs-container">';
        foreach ($tabs as $slug => $label) {
            $output .= '<div class="tab ' . ($slug == $current_slug ? 'active' : '') . '">';
            $output .= '<a href="' . admin_url('admin.php?page='.$slug) . '">' . $label . '</a>';
            $output .= '</div>';
        }
        $output .= '</div>'; // end .tabs-container

        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function render_postbox_open($title = '') {
        ?>
        <div class="postbox">
            <div class="handlediv" title="Click to toggle"><br/></div>
            <h3 class="hndle nm-hndle"><span><?php echo $title; ?></span></h3>
            <div class="inside">
        <?php
    }

    public function render_postbox_close() {
        echo '</div>'; // end .inside
        echo '</div>'; // end .postbox
    }

    public function render_container_open($extra_class = '', $echo = TRUE) {
        $output = '';
        $output .= '<div class="metabox-holder ' . $extra_class . '">';
        $output .= '  <div class="postbox-container nm-postbox-container">';
        $output .= '    <div class="meta-box-sortables ui-sortable">';

        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function render_container_close($echo = TRUE) {
        $output = '';
        $output .= '</div>'; // end .ui-sortable
        $output .= '</div>'; // end .nm-postbox-container
        $output .= '</div>'; // end .metabox-holder

        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }

    public function render_checkbox($name, $val_option, $key) {
        $checked = '';
        if ($val_option) {
            if (isset($val_option[$key]) && $val_option[$key] == TRUE) {
                $checked = 'checked';
            }
        }
        echo '<input type="checkbox" name="' . $name .'" value="checked" ' . $checked . '/>';
    }

    public function render_nm_logos() {
        ?>
            <div class="nm-logo one-fourth">
                <a href="http://nuancedmedia.com/" target="_blank">
                    <img src="http://nuancedmedia.com/wp-content/uploads/2014/04/nm-logo-black.png" />
                </a>
            </div>
            <div class="nm-social-media-links-container three-fourths">
                <div class="nm-social-media-link nm-facebook-link">

                </div>
                <div class="nm-social-media-link nm-google-plus-link">
                    <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) {return;}
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                    <div id="wp-meetup-social">
                        <div class="fb-like" data-href="https://www.facebook.com/NuancedMedia" data-send="false" data-layout="button_count" data-width="100" data-show-faces="true"></div><br><br>
                        <g:plusone annotation="inline" width="216" href="http://nuancedmedia.com/"></g:plusone><br>
                        <!-- Place this tag where you want the +1 button to render -->
                        <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="google-plus-container" style="width: 100%; overflow: hidden;">
                <!-- Google Plus Link -->
                <script src="https://apis.google.com/js/platform.js" async defer></script>
                <div class="g-page" data-href="//plus.google.com/u/0/103543858548099057697" data-rel="publisher"></div>
            </div>
            <div class="nm-plugin-links">
                <?php $this->render_postbox_open('WP Meetup Links') ?>
                <ul class="wp-meetup-link-list">
                    <li><a href="http://wordpress.org/extend/plugins/wp-meetup/" target="_blank">Wordpress.org Plugin Directory listing</a></li>
                    <li><a href="http://nuancedmedia.com/wordpress-meetup-plugin/" target="_blank">WP Meetup Plugin homepage</a></li>
                </ul>
                <?php $this->render_postbox_close(); ?>
            </div>
        <?php
    }

    public function insert_website_link() {
        ?>
            <div class="website-link">
            <a href="http://plugins.nuancedmedia.com/wordpress-meetup-plugin/" target="_BLANK"><button style="padding: 13px; background-color: #9f06c6; border-radius: 5px; color: #ffffff; border: none; width: 100%;">Visit Plugins Website</button></a>
            <a href="http://plugins.nuancedmedia.com/installation-assistance/" target="_BLANK"><button style="padding: 13px; background-color: #069fc6; border-radius: 5px; color: #ffffff; border: none; width: 100%; margin-top: 13px;">Get Installation Assistance</button></a>
            </div>
        <?php
    }
}
