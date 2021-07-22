<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aggregate filling
    |--------------------------------------------------------------------------
    |
    | Here you may define a default connection.
    |
    | Supported: "sync", "redis"
    |
    */

    'filling' => env('ESPLORA_FILLING', 'sync'),

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
        \Esplora\Tracker\Rules\OnlyRepresentation::class,
        \Esplora\Tracker\Rules\WeedOutDuplicates::class,
        \Esplora\Tracker\Rules\WeedOutBots::class,
        \Esplora\Tracker\Rules\WeedOutFiles::class,
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

    'redis' => env('ESPLORA_REDIS', 'default'),

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


     /*
     |--------------------------------------------------------------------------
     | Interval time
     |--------------------------------------------------------------------------
     |
     | Time in hours, which define interval aggregate data
     | Minimum value - 1 hour
     |
     */

    'interval' => 24,

    /*
    |--------------------------------------------------------------------------
    | Pruning Models
    |--------------------------------------------------------------------------
    |
    | Time in days when have to clean up the records of databases
    | by deleting the oldest and useless data.
    |
    */

    'pruning' => 365,

];
