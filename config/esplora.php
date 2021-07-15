<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aggregate filling
    |--------------------------------------------------------------------------
    |
    | ...
    |
    | Supported: "sync", "redis"
    |
    */

    'filling' => 'redis',

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    |
    | You can exclude tracking, for example, if the user just refreshed
    | the page. Or not to record visits to search engines.
    |
    */

    'rules' => [
        \Esplora\Tracker\Rules\RequestingRepresentation::class,
        \Esplora\Tracker\Rules\RequestingDuplicate::class,
        \Esplora\Tracker\Rules\RequestingBot::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Connection
    |--------------------------------------------------------------------------
    |
    | This is the name of the Redis connection where Esplora will store the
    | meta information required for it to function.
    |
    */

    'redis' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | This configuration options determines the database driver that will
    | be used to store visitor's data.
    |
    */

    'database' => env('DB_CONNECTION', 'mysql'),

];
