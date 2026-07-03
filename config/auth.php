<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    ///////////////////////////--guard--///////////////

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'professional_users',
            'hash' => false,
        ],

       'parent_api' => [
        'driver' => 'jwt',
        'provider' => 'parents',
    ],


    ],

    ///////////////////////////--guard--///////////////

    ///////////////////////////--Provider--///////////////

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'professional_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Api\Professional_user::class,
        ],

        'parents' => [
            'driver' => 'eloquent',
            'model' => App\Models\SchoolParent_Model::class,
        ],
    ],

    ///////////////////////////--Provider--///////////////




    ///////////////////////////--passwords--///////////////


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'professional_users' => [
            'provider' => 'professional_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'client_users' => [
            'provider' => 'client_users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    ///////////////////////////--passwords--///////////////


    'password_timeout' => 10800,

];
