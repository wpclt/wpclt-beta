<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * v0.0.1 - 2014-04-11
 *      - Initial Class Creation
 */

/**
 * dump function for debug
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE) {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left; width: 100% !important; font-size: 12px !important;">' . $label . ' => ' . $output . '</pre>';
        if ($echo == TRUE) {
            echo $output;}else {return $output;}
    }
}
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);exit;
    }
}


/**
 * Given a string, returns whether or not the string is json encoded
 * @param string $string
 * @return bool
 */
if (!function_exists('nm_is_json')) {
    function nm_is_json($string) {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
}


/**
 * Given a string, returns whether or not this string is xml encoded
 * @param string $string
 * @return bool
 */
if (!function_exists('nm_is_xml')) {
    function nm_is_xml($string) {
        return (is_string($string) && (is_object(simplexml_load_string($string)))) ? true : false;
    }
}

/**
 * Given a string, returns a form of the string with the beginning of every word capitalized.
 * @param string $string
 * @return string
 */
if (!function_exists('nm_strtocap')) {
    function nm_strtocap($string) {

        $string = trim($string);
        $string = strtolower($string);
        $string = ucwords($string);

        return $string;
    }
}

if (!function_exists('nm_get_year')) {
    function nm_get_year() {
        return intval(date('Y'));
    }
}

if (!function_exists('nm_reset_permalinks')) {
    function nm_reset_permalinks() {
        if (!class_exists('WP_Rewrite')) {
            require_once(ABSPATH . '/wp-includes/rewrite.php');
        }
        $wp_rewrite->flush_rules(TRUE);
    }
}

/**
 * Short circuit function for POST requests. mostly used for
 * querying google, since wp_remote_post does not play nicely with
 * the goog's. This is likely not very comptaible with multiple machines
 * @param string $url
 * @param array $postdata A $key => $val array of POST data
 * @return string A json- or xml- encoded string on success, or NULL on fail
 */
if (!function_exists('nm_remote_post')) {
    function nm_remote_post($url, $postData = array()) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
                CURLOPT_POSTFIELDS => json_encode($postData)
            )
        );
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code == '200' && !empty($response)) {
            //return json_decode($response, true);
            return $response;
        }
        return NULL;
    }
}

if (!function_exists('nm_shift_unix')) {
    function nm_shift_unix($shift_int, $increment, $time = NULL) {
        if (is_null($time)) {
            $time = time();
        }
        $date = getdate($time);

        // Increment by DAYS
        if ($increment == 'day') {
            $days = $shift_int;
            $month = $date['mon'];
            $year = $date['year'];
            $dim = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            if (($days + $date['mday']) > $dim) {
                $new_time = nm_shift_unix( 1, 'month', $time);
                $days = $days - $dim;
                $newer_time = nm_shift_unix($days, 'day', $new_time);
                $date = getdate($newer_time);
            }
            else if (($days + $date['mday']) <= 0) {
                $new_time = nm_shift_unix( -1, 'month', $time);
                $days = $days + cal_days_in_month(CAL_GREGORIAN, $month - 1, $year);
                $newer_time = nm_shift_unix($days, 'day', $new_time);
                $date = getdate($newer_time);
            }
            else {
                $date['mday'] = $date['mday'] + $days;
            }
        }

        // Increment by WEEKS
        if ($increment == 'week') {
            $new_time = nm_shift_unix( $shift_int * 7, 'day', $time);
            $date = getdate($new_time);
        }

        // Increment by MONTHS
        if ($increment == 'month') {
            $date['mon'] = $date['mon'] + $shift_int;
            if ($date['mon'] > 12) {
                $new_time = nm_shift_unix( 1, 'year', $time);
                $date = getdate($new_time);
                $date['mon'] = $date['mon'] + $shift_int - 12;
            }
            if ($date['mon'] <= 0) {
                $new_time = nm_shift_unix( -1, 'year', $time);
                $date = getdate($new_time);
                $date['mon'] = $date['mon'] + $shift_int + 12;
            }
        }

        // Increment by YEARS
        if ($increment == 'year') {
            $date['year'] = $date['year'] + $shift_int;
        }

        $return_value = mktime( $date['hours'], $date['minutes'], $date['seconds'], $date['mon'], $date['mday'], $date['year']);
        return $return_value;
    }
}

if (!function_exists('nm_clean_input')) {
    function nm_clean_input($string) {
        $output = strip_tags($string);
        $output = htmlspecialchars($output);
        $output = mysql_real_escape_string($output);
        return $output;
    }
}

if (!function_exists('echo_clear')) {
    function echo_clear() {
        echo '<div class="clear"></div>';
    }
}

if (!function_exists('echo_div_close')) {
    function echo_div_close() {
        echo '</div>';
    }
}

if (!function_exists('echo_div')) {
    function echo_div($array) {
        $output = '<div';
        foreach ($array as $key=>$value) {
            $output .= ' ' . $key . '="' . $value . '"';
        }
        $output .= '>';
        echo $output;
    }
}
