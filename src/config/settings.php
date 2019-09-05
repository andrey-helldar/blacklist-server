<?php

return [

    /*
     * Server is type.
     *
     * Available: "local", "remote".
     */

    'type' => 'remote',

    'connection' => env('DB_CONNECTION', 'mysql'),

    /*
     * Days
     */

    'ttl' => 7,

    'ttl_multiplier' => 3,

];
