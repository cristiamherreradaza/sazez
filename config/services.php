<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),             // Your Github Client ID
        'client_secret' => env('GITHUB_CLIENT_SECRET'),     // Your Github Client Secret
        'redirect' => env('GITHUB_CALLBACK'),               // Your Github Callback Url
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),           // Your Facebook Client ID
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),   // Your Facebook Client Secret
        'redirect' => env('FACEBOOK_CALLBACK'),             // Your Facebook Callback Url
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),           // Your Google Client ID
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),   // Your Google Client Secret
        'redirect' => env('GOOGLE_CALLBACK'),             // Your Google Callback Url
    ],

];
