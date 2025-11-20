<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chaos Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether the chaos command is enabled.
    | It is recommended to keep this disabled in production unless you
    | really know what you are doing.
    |
    */
    'enabled' => env('CHAOS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Target Paths
    |--------------------------------------------------------------------------
    |
    | The paths where the chaos command will look for files to delete.
    |
    */
    'paths' => [
        storage_path('logs'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Target Extensions
    |--------------------------------------------------------------------------
    |
    | If specified, only files with these extensions will be deleted.
    | Leave empty to target all files.
    |
    */
    'extensions' => [],

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | The filesystem disk to use.
    |
    */
    'disk' => 'local',
];
