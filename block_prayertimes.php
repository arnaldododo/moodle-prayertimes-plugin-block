<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * 
 *
 * @package   block_prayertimes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_prayertimes extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_prayertimes');
    }

    function has_config()
    {
        return true;
    }

    function get_content()
    {
        global $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.aladhan.com/v1/timingsByAddress?address=Jakarta,ID",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response, true);

        $showall = get_config('block_prayertimes', 'showall');
        $prayer_time = "";
        if ($showall) {
            //Print all the time from API
            foreach ($response['data']['timings'] as $key => $value) {
                $prayer_time .= $key . ": " . $value . "<br>";
            }
        } else {
            //Print only five prayer times
            $prayer_time .= "Fajr: " . $response['data']['timings']['Fajr'] . "<br>";
            $prayer_time .= "Dhuhr: " . $response['data']['timings']['Dhuhr'] . "<br>";
            $prayer_time .= "Asr: " . $response['data']['timings']['Asr'] . "<br>";
            $prayer_time .= "Maghrib: " . $response['data']['timings']['Maghrib'] . "<br>";
            $prayer_time .= "Isha: " . $response['data']['timings']['Isha'] . "<br>";
        }

        $this->content = new stdClass;
        $this->content->text = $prayer_time;
        $this->content->footer = '';
        return $this->content;
    }
}
