<?php

return [

    /*
     * Default, true.
     */

    'use_routes' => true,

    'friendly' => true,

    'connection' => env('DB_CONNECTION', 'mysql'),

    /*
     * Days
     */

    'ttl' => 7,

    /*
     * Default, 3.
     */

    'ttl_multiplier' => 3,

];
