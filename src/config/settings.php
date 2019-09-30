<?php

return [

    /*
     * Allow external routes?
     *
     * Default, true.
     */

    'use_routes' => true,

    /*
     * Allow sending your spammers to the package developer server?
     *
     * Default, true.
     */

    'friendly' => true,

    /*
     * The name of the connection to the database that stores information about spammers.
     */

    'connection' => env('DB_CONNECTION', 'mysql'),

    /*
     * The default number of days for which the user should be added to the spam list.
     *
     * Default, 7.
     */

    'ttl' => 7,

    /*
     * User multiplication factor for repeated violations.
     *
     * For example, they marked the user once - he will be included in the stop list for 7 days.
     * In case of repeated violation, the period will be 7 * 3 = 21 days. And so on.
     *
     * Default, 3.
     */

    'ttl_multiplier' => 3,

    /*
     * The values shown here will not be blacklisted.
     *
     * By default, exceptions are added:
     *   127.0.0.1
     *   <ip-address of this server>
     *   <url of this server>
     *
     * Default, [].
     */

    'except' => [],

];
