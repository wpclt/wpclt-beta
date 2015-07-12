<?php
/**
 * Version 0.0.3
 * Author: Austin Adamson
 * Created: 2014-03-19
 * Last Revised: 2014-03-19
 *
 * CHANGELOG:
 * v0.0.1 - 2014-03-19
 *      - Initial Class Creation
 */

require_once ABSPATH . 'wp-includes/pluggable.php';

class NMCustomPost {


    public function __construct() {
        add_action('init', array(&$this, 'create_post_type'));
    }

    /**
     * This function creates the a custom Post type and capitol
     */
    function create_post_type() {
        global $wpdb;
        if ($this->pt) {

            $this->pt = strtolower($this->pt);
            $post_type = nm_strtocap($this->pt);


            $settings = array(
                'labels' => array(
                    'name' => __( $post_type ),
                    'singular_name' =>  __( $post_type . ' Page'),
                ),
                'public' => TRUE,
                'has_archive' => TRUE,
                'show_ui'   => FALSE,
                'show_in_menu' => FALSE,
                'supports' => array('title', 'editor', 'revisions'),
                'menu_position' => 5,
            );
            register_post_type($post_type, $settings);
        }
        else {
            echo '<div class="error">' . __('Development Error: The pt variable needs to be overwritten in each specific PT file.') . '</div>';
        }
    }

    /**
     * Checks the database for the post and adds/updates accordingly.
     * @param type $post_array
     * @return type The WP Post Id for the newly created post
     */
    public function add_post($post_array) {
        if (!$this->post_db) {
            echo '<div class="error">' . __('Variable post_db must be declared inorder to add custom posts to wp_posts.') . '</div>';
            return;
        }
        if (isset($post_array['ID']) || isset($post_array['id'])) {
            $this->update_existing_post($post_array);
            if (isset($post_array['ID'])) {
                $id = $post_array['ID'];
            } else {
                $id = $post_array['id'];
            }
        }
        else {
            $id = 0;
            $id = $this->create_event_post($post_array);
        }
        return $id;

    }

    /**
     * Creates a NEW event post of this post type
     * @param array $post_array this must be an array that containing $keys: content, name
     * @return mixed The WordPress post ID of the created post
     */
    function create_event_post($post_array = array()){
        /* Creates new event posts and returns the WordPress post ID */
        $post_id = wp_insert_post($post_array, true);
        return $post_id;

    }

    /**
     * @param array $post_array this must be an array that containing $keys: content, name
     */
    function update_existing_post($post_array = array()){
        /*  Updates all existing posts */
        wp_update_post($post_array);
    }
}
