<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redirection Routes
    |--------------------------------------------------------------------------
    |
    | Here you can define the routes that the ValidateShareableLink
    | middleware will redirect to if one its checks fail.
    |
    | 'inactive' => The link is marked as inactive in the database.
    | 'expired' => The links expiration date has passed.
    | 'password_protected' => The user tried to access a password protected link.
    |
    */

    'redirect_routes' => [
        'inactive' => '/shared/inactive',
        'expired' => '/shared/expired',
        'password_protected' => '/shared/password',
    ],

    /*
    |--------------------------------------------------------------------------
    | Hashids Configuration
    |--------------------------------------------------------------------------
    |
    | This is where you can configure the underlying Hashids library to
    | your liking.
    |
    */

    'hashids' => [
        'min_hash_length' => 10,
        'salt' => env('SHAREABLE_LINK_SALT', env('APP_KEY')),
        'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
    ],
];
