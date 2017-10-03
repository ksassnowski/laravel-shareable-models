<?php

return [

    'base_url' => '/shared',

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
];
