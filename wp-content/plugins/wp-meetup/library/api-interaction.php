<?php

/**
 * Version 0.0.1
 * Author: Austin Adamson
 * Created: 2014-03-17
 * Last Revised: 2014-03-17
 *
 * CHANGELOG:
 * v1.0.0 - 2014-03-17
 *    - initial class creation
 */


class ApiInteraction {



    /**
     * This class must be overwritten in each of the specific API files.
     *
     * @since 0.0.1
     * @param $prefix A string or array
     * @param $action String
     * @param null $input Array
     * @return null
     */
    public function get_results($prefix, $action, $input = null) {

        echo '<div class="error">' . __('Development Error: The get_results function needs to be overwritten in each specific API file.') . '</div>';
        $results = null;

        return $results;
    }

    /**
     * Simply calls the pre existing WordPress remote get functions.
     *
     * @since 0.0.1
     * @param $url String: the URL of the REST request.
     * @return mixed The body of the API request.
     */
    public function remote_get($url) {
        $results = wp_remote_get($url);
        $results = wp_remote_retrieve_body($results);
        return $results;
    }

    /**
     * Checks to see if this API Request has already been made.
     * If yes then it pulls results from the database, else it calls the query_api function.
     *
     * @since 0.0.1
     * @param $url String: the URL of the REST request.
     * @return mixed The body of the API request either from the API of the API_DB
     */
    public function query_api($url) {

        $this->api_db->select('id');
        $this->api_db->where('request', $url);
        $id = $this->api_db->get();
        $this->api_db->reset_query();
        if ($id) {
            $this->api_db->select('result');
            $this->api_db->where('id', $id[0]->id);
            $results = $this->api_db->get();
            $results = $results[0]->result;

        }

        else {
            $results = $this->call_remote_api($url);
            if (is_string($results) && strlen($results) < 4294967295) {
                $data = array(
                    'request' => $url,
                    'result' => $results,
                );
                $saved = $this->api_db->save($data, '');
                if (!$saved) {
                    echo '<div class="error">' . __('Development Error: The was an error inserting a new row in the api_db.') . '</div>';
                }
            }

        }
        return $results;

    }
}