<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'google' => [
        'client_id' => '946883670013-0j7egpqm5k04l3lhb578u6a6548tr8o3.apps.googleusercontent.com',
        'client_secret' => 'LB1W66VdjFEHGTGMFwbbpWy5',
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],

//    'google' => [
//        'client_id' => '946883670013-ebumk4r2s5i65a9708789ks2toiudga6.apps.googleusercontent.com',
//        'client_secret' => 'oMYxSrAJIqr4AGwfQUzaNZb1',
//        'redirect' => 'http://dev-ocplbase.eserve.org.bd/oauth/google/callback',
//    ],

    'facebook' => [
        'client_id' => '464015370644613',
        'client_secret' => '4ef62f60c63ba7b1119354b66ecdfa32',
        'redirect' => 'http://localhost:8000/auth/facebook/callback',
    ],

];
