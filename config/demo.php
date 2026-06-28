<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo Admin Email
    |--------------------------------------------------------------------------
    |
    | The email used when seeding the admin account for quick login testing.
    | Override with DEMO_USER_EMAIL in your .env file.
    |
    */

    'email' => env('DEMO_USER_EMAIL', 'admin@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Demo Error Triggers
    |--------------------------------------------------------------------------
    |
    | Manual error scenarios for learning and monitoring tools like Nightwatch.
    |
    */

    'errors_enabled' => env('DEMO_ERRORS_ENABLED', true),

    'webhook_url' => env('DEMO_WEBHOOK_URL', 'https://httpstat.us/500'),

];
