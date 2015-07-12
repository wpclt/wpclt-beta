<?php

/*
 * Custom CRUD methods to make database queries
 * a bit easier and a bit more consistent
 * v1.2.2
 */

class NMDB {

    var $sqltable = 'wp_posts';

    // used for get queries
    var $where = '';
    var $order_by = '';
    var $limit = '';
    var $offset = '';
    var $select = '*';
    var $current_query = '';
    var $keep_query_after_get = FALSE;

    var $debug_queries = FALSE;

    function __construct() {
        global $wpdb;
        $tableSearch = $wpdb->get_var("SHOW TABLES LIKE '$this->sqltable'");
        if ($tableSearch != $this->sqltable) {
            $this->create_update_database();
        }
        //add_action('init', array(&$this, 'init'));
    }

    function init() {
        global $wpdb;
        $tableSearch = $wpdb->get_var("SHOW TABLES LIKE '$this->sqltable'");
        if ($tableSearch != $this->sqltable) {
            $this->create_update_database();
        }
    }

    function create_update_database() {
        echo '<div class="alert">You need to override the function create_update_database!</div>';
    }

    function keep_query() {
        $this->keep_query_after_get = TRUE;
    }

    function reset_query() {
        if ($this->keep_query_after_get) {
            $this->keep_query_after_get = FALSE;
        } else {
            $this->select = '*';
            $this->where = '';
            $this->order_by = '';
            $this->limit = '';
            $this->offset = '';
            $this->current_query = '';
        }
    }

    function select($key_or_array) {
        if (is_array($key_or_array)) {
            foreach ($key_or_array as $key) {
                $this->select($key);
            }
        } else {
            // check to see if we need to "escape" the column name or not
            $escaped_key = (strpos($key_or_array, '(*)') === FALSE ? '`' . $key_or_array . '`' : $key_or_array);
            if ($this->select == '*') {
                $this->select = $escaped_key;
            } else {
                $this->select .= ', ' . $escaped_key;
            }
        }
    }

    function where($key_or_array, $value = NULL) {
        if (is_array($key_or_array)) {
            foreach ($key_or_array as $key => $val) {
                $this->where($key, $val);
            }
        } else {
            if ($this->where == '') {
                $this->where = ' WHERE ' . $key_or_array . ' = \'' . addslashes($value) . '\'';
            } else {
                $this->where .= ' AND ' . $key_or_array . ' = \'' . addslashes($value) . '\'';
            }
        }
    }

    function order_by($key, $sort = 'ASC') {
        $this->order_by = ' ORDER BY `' . $key . '` ' . $sort;
    }

    function limit($row_count, $offset = NULL) {
        if ($offset != NULL) {
            $this->offset($offset);
        }
        $this->limit = ' LIMIT ' . $row_count;
    }

    function offset($offset) {
        $this->offset = ' OFFSET ' . $offset;
    }
    
    /**
     * Builds a query based on previous inputs and method calls and
     * retrieves and returns the data. If $var is TRUE, it will just get
     * a 'var' result. 
     * Otherwise:
     * If $id is not NULL or if $single is TRUE, then it will return a single
     * row. Otherwise, it will return an array of objects (representing each
     * row, of course.)
     * 
     * @global WPDB $wpdb
     * @param INT $id
     * @param BOOL $single
     * @param BOOL $var
     * @return MIXED either an array of objects, an object, or NULL
     */
    function get($id = NULL, $single = FALSE, $var = FALSE) {
        global $wpdb;
        $output = NULL;
        if ($id != NULL) {
            $this->where('id', $id);
        }
        $this->build_get_query();
        if ($var) {
            $output = $wpdb->get_var($this->current_query);
        } else {
            if ($id == NULL) {
                if ($single) {
                    $output = $wpdb->get_row($this->current_query);
                } else {
                    $output = $wpdb->get_results($this->current_query);
                }
            } else {
                $output = $wpdb->get_row($this->current_query);
            }
        }
        $this->reset_query();
        return $output;
    }

    function get_var() {
        return $this->get(NULL, FALSE, TRUE);
    }

    function build_get_query() {
        $this->current_query = '';
        $this->current_query .= 'SELECT ' . $this->select . ' FROM ' . $this->sqltable;
        $this->current_query .= $this->where;
        $this->current_query .= $this->order_by;
        $this->current_query .= $this->limit;
        $this->current_query .= $this->offset;
        if ($this->debug_queries && function_exists('dump')) { dump($this->current_query, 'current_query'); }
    }

    function save($data, $id = NULL) {
        global $wpdb;
        if (!$data || !count($data)) {
            return FALSE;
        }

        if ($id != NULL) {
            // update row
            $result = $wpdb->update($this->sqltable, $data, array('id' => $id));
        } else {
            // insert new row
            $result = $wpdb->insert($this->sqltable, $data);
        }
        return $result;
    }

    function delete($id) {
        global $wpdb;
        $wpdb->query('DELETE FROM ' . $this->sqltable . ' WHERE id="' . $id . '"');
    }

    function debug_print_database() {
        $data = $this->get();
        $row = 0;
        echo '<table>';
        foreach ($data as $result) {
            if ($row == 0) {
                echo '<thead><tr>';
                foreach ($result as $key=>$value) { echo '<th>' . $key . '</th>'; }
                $row++;
                echo '</tr></thead>';
            }
            echo '<tr>';
            foreach ($result as $key=>$value) {
                $val = $value;
                if (strlen($val) > 100) {
                    $val = substr(strip_tags($value), 0, 100) . ' [...] ';
                }
                echo '<td valign="top">' . $val . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    
    /**
     * Functions Added by Austin
     */
    
    public function delete_table() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $this->sqltable");
    }
}
