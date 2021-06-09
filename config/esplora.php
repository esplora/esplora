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

    'filling' => 'sync',

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'rules' => [
        \Esplora\Analytics\Rules\RequestingRepresentation::class,
        \Esplora\Analytics\Rules\RequestingDuplicate::class,
        \Esplora\Analytics\Rules\RequestingBot::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Batch Insertion
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'batch' => 1,


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
    | Вy default Esplora does not use a prefix to create new columns in
    | the database. However, you can add them by changing this value.
    | This must be done before performing migrations.
    | Otherwise, they must be rolled back.
    |
    */

    'database' => env('DB_CONNECTION', 'mysql'),

];
