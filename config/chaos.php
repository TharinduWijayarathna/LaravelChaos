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
    | Chaos Interval
    |--------------------------------------------------------------------------
    |
    | How often chaos should occur. Supported values:
    | - 'minute' (every minute)
    | - 'hour' (every hour)  
    | - 'day' (every day)
    | - 'week' (every week)
    | - 'month' (every month)
    |
    */
    'interval' => env('CHAOS_INTERVAL', 'week'),

    /*
    |--------------------------------------------------------------------------
    | Target Paths
    |--------------------------------------------------------------------------
    |
    | The paths where the chaos command will look for files to delete.
    | WARNING: Be extremely careful with these paths as files will be 
    | permanently deleted!
    |
    */
    'paths' => [
        app_path(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Target Extensions
    |--------------------------------------------------------------------------
    |
    | If specified, only files with these extensions will be deleted.
    | Leave empty to target all files.
    | Example: ['php', 'txt', 'log']
    |
    */
    'extensions' => ['php'],

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
