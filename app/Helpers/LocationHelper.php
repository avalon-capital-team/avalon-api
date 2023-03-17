<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * @throws \Exception
     */
    public static function getInfo()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '';
        }

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $agent = '--';
        }

        $city = '';
        $country = '';


        if ($ip != '') {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            if (isset($details->city) && $details->city) {
                $city = $details->city;
                $country = $details->country;
            }
        }


        return [
            'ip' => $ip,
            'agent' => $agent,
            'country' => $country,
            'city' => $city
        ];
    }
}
