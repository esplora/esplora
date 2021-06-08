<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Esplora Redis Connection
    |--------------------------------------------------------------------------
    |
    | This is the name of the Redis connection where Esplora will store the
    | meta information required for it to function.
    |
    */

    'use' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Batch Insertion
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */

    'batch' => 200,

    /*
    |--------------------------------------------------------------------------
    | Database prefix
    |--------------------------------------------------------------------------
    |
    | Вy default Esplora does not use a prefix to create new columns in
    | the database. However, you can add them by changing this value.
    | This must be done before performing migrations.
    | Otherwise, they must be rolled back.
    |
    */

    'prefix' => '',

];
