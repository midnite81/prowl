<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Url
    |--------------------------------------------------------------------------
    | This is the url which connects to the API.
    |
    */

    'apiUrl' => 'https://api.prowlapp.com/publicapi',

    /*
    |--------------------------------------------------------------------------
    | Default Device Key
    |--------------------------------------------------------------------------
    | If no api key is set when calling a message then this is the key that
    | will be used by default.
    |
    */
    'defaultKey' => 'iphone',


    /*
    |--------------------------------------------------------------------------
    | Device Keys
    |--------------------------------------------------------------------------
    | An array of api keys you can reference when calling the setApiKeys function.
    |
    */
    'keys' => [
        'iphone' => '<iphone_api_key>',
        'ipad' => '<ipad_api_key>'
    ],
];
