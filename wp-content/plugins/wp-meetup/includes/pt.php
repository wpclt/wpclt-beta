<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupPostType extends NMCustomPost {

    /**
     *
     * @var WPMeetup
     */
    var $core;
    
    /**
     *
     * @var STRING 
     */
    var $pt;
    
    /**
     *
     * @var WPMeetupPostsDB
     */
    var $post_db;

    /**
     * 
     * @param WPMeetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        $this->pt = $core->post_type;
        $this->post_db = $this->core->post_db;
        parent::__construct();
    }
}