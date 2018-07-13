<?php

if ( ! function_exists('config')) {

    /**
     *
     */
    function config($key)
    {
        if ($key == 'prowl') {
            return [
                'apiUrl' => 'https://api.prowlapp.com/publicapi',
                'defaultKey' => 'iphone',
                'keys' => [
                    'iphone' => '<iphone_api_key>',
                    'ipad' => '<ipad_api_key>'
                ],
            ];
        }
        if ($key == 'prowl.keys') {
            return [
                'iphone' => 'test',
                'ipad' => 'test',
            ];
        }
        return [];
    }

}