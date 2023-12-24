<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Settings for all PSP Scenario Cases
    |--------------------------------------------------------------------------
    |
    |   Could be overwritten by the case specific settings.
    |
    |   namespace - is responsible for the psp service class location
    |
    |   case.field_name - identifies the request field name where
    |     will be stored case.amount
    |
    |   case.amount - is responsible for the test case scenario identification
    |     based on the amount.
    |     Case-sensitive method name inside the PSP service class.
    |     May have different amounts for the same case scenario to cover limits of
    |       - regular currencies like USD
    |       - high amount currencies like INR where normal is 1,000,000
    |       - cryptocurrencies like BTC where normal is 0.0000035
    */

    'default_settings' => [
        'namespace' => 'App\\Services\\Processors\\',
        'case' => [
            'field_name' => 'amount',
            'amount' => [
                '50' => 'sale',
                '5000000' => 'sale',
                '0.00005' => 'sale',

                '45' => 'payout',
                '40' => 'withdraw'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Psp specific settings
    |--------------------------------------------------------------------------
    |
    | These settings will be used at the first place.
    | Same description as for the default_settings.
    | Everything is case-sensitive.
    |
    */
    'ExamplePsp' => [
        'namespace' => 'App\\Services\\Processors\\',
        'notification_url' => [
            'card' => 'https://praxis.com/card/',
            'wallet' => 'https://praxis.com/wallet/{XXX}/{YYY}',
        ],
        'case' => [
            'field_name' => 'amount',
            'amount' => [
                '50' => 'payout',
                '60' => 'someMethodName',
                '45' => 'payoutExamplePsp',
            ],
        ],
    ],
];
