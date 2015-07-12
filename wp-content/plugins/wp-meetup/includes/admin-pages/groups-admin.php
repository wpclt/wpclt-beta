<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * v0.0.1 - 2014-04-11
 *      - Initial Class Creation
 * 2014-04-21
 *      - Added Extention of WPMAdminPage
 */

class WPMeetupGroupsAdmin extends WPMeetupAdminPage{

    /**
     *
     * @var WPMeetup 
     */
    var $core;
    
    /**
     *
     * @var WPMeetupAPI 
     */
    var $api;
    
    /**
     *
     * @var GroupDB
     */
    var $db;
    
    /**
     * 
     * @param WPMeetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        $this->db = $core->group_db;
        $this->api = $core->api;
        add_submenu_page(
            'wp_meetup_settings',
            'Groups',
            'Groups',
            'administrator',
            'wp_meetup_groups',
            array($this, 'create_page')
        );
    }
    
    public function display_page() {
        $this->update_colors_and_groups();
        $this->add_groups();
        $groups = $this->db->get();
        $this->render_postbox_open('Group List');
        if (empty($groups)) {
            echo '<div class="nm-error">No groups have been added yet</div>';
        }
        else {
            $this->display_groups($groups);
        }
        $this->render_postbox_close();
        $this->render_postbox_open('Add New Groups');
        $this->echo_add_group();
        $this->render_postbox_close();
    }
    
    /**
     * Adds groups to db
     */
    private function add_groups() {
        if (isset($_POST['update']) && $_POST['update'] == 'wpm-update-groups') {
            $groups = $_POST['groups'];
            $group_array = explode(',', $groups);
            foreach ($group_array as $group) {
                $group = str_replace('http://www.meetup.com/', '', $group);
                $group = str_replace('www.meetup.com/', '', $group);
                $group = str_replace('/', '', $group);
                $group = str_replace('#', '', $group);
                $group = str_replace(':', '', $group);
                $group = str_replace(' ', '', $group);
                $this->add_group($group);
            }
            $_POST['update'] = NULL;
            $this->core->trigger->execute_update();
        }
    }
    
    /**
     * Adds an individual group to the DB
     * @param type $group
     */
    private function add_group($group) {
        $results = $this->api->get_results(array('group_urlname'=> $group), 'groups');
        if (nm_is_json($results)) {
            $results = json_decode($results);
        } 
        if (!isset($results->results['0'])) {
            echo '<div class="error">Results not set. Please enter a correct group urlname.</div>';
            return;
        }
        $result = $results->results['0'];
        if (is_null($result)) {
            echo '<div class="error">No results found. Please enter a correct group urlname.</div>';
            return;
        }
        $group_data = array(
            'group_name' => $result->name,
            'group_slug' => $result->urlname,
            'group_id' => $result->id,
            'color' => '#656565',
        );
        $this->db->select('id');
        $this->db->where($group_data);
        $id = $this->db->get();
        if (empty($id)) {$id = NULL;}
        else{$id = $id[0];$id = $id->id;}
        $this->db->save($group_data, $id);
    }
    
    
    
    /**
     * Retrieves the updates from $_POST and filters to individual updates
     */
    private function update_colors_and_groups() {
        if (isset($_POST['update']) && $_POST['update'] == 'wpm-update-color') {
            foreach ($_POST as $key=>$value) {
                if ($key != 'update') {
                    $this->update_individual_entry($key, $value);
                }
            }
        }
    }
    
    /**
     * Updates each row of the DB as needed
     * @param STRING $key
     * @param STRING $value
     */
    private function update_individual_entry($key, $value) {
        $key = explode('-', $key);
        if ($key[0] == 'color') {
            $data = array(
                'color' => $value,
            );
            $this->db->save($data, $key[1]);
        }
        if ($key[0] == 'delete') {
            $this->db->select('group_id');
            $this->db->where(array('id'=>$key[1]));
            $group_ids = $this->db->get();
            foreach ($group_ids as $id_object) {
                $group_id = $id_object->group_id;
                $this->core->event_db->select('id');
                $this->core->event_db->where(array('group_id'=>$group_id));
                $ids = $this->core->event_db->get();
                foreach ($ids as $id) {
                    $this->core->event_db->delete($id->id);
                    
                }
            }
            $this->db->delete($key[1]);
        }
    }
    
     /**
     * Outputs the form used to add groups
     */
    private function echo_add_group() {
        ?>
        <form action="" method="post">
            <input type="hidden" name="update" value="wpm-update-groups">
            <label>Add a new group:</label>
            <input type="text" name="groups" value="" placeholder="group_url_1, group_url_2, group_url_3">
            <input type="submit">
        </form>
        <?php
    }
    
    /**
     * Creates and returns the table/form display of the groups
     * @param ARRAY $groups
     * @return STRING
     */
    private function display_groups($groups) {
        ?>
        <form method="post" action="">
        <table class="group-table">
            <tr>
                <th>Name</th>
                <th>Urlname</th>
                <th>ID</th>
                <th>Color</th>
                <th>Delete</th>
            </tr>
        <?php
        foreach ($groups as $group) {
            ?>
            <tr>
            <td> <?php echo $group->group_name ?> </td>
            <td> <?php echo $group->group_slug ?> </td>
            <td> <?php echo $group->group_id ?> </td>
            <td><input type="color" name="color-<?php echo $group->id ?>" value="<?php echo $group->color ?>"</td>
            <td><input type="checkbox" name="delete-<?php echo $group->id ?>" value="checked"><label>Delete</label></td></tr>
            <?php
        }
        ?>
        </table>
            <input type="hidden" name="update" value="wpm-update-color">
            <input type="submit" value="Update Groups">
        </form>
        <?php
    }
}