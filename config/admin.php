<?php

return [

    /*
     * Route configuration.
     */
    'route' => [
        'prefix' => 'admin',
        'namespaces' => 'App\\Admin\\Controllers',
        'routes' => app_path('routes/admin.php')
    ],

    'pjax' => true,

    /*
     * Laravel-admin upload setting.
     */
    'file' => [
        'disk' => 'public',
        'onlyMimes' => ['image'],
        'options' => [
            'uploadOverwrite' => false,
            'tmbURL' => '/.tmb',
            'uploadAllow' => ['image'],
            'uploadOrder' => ['allow'],
            'uploadMaxSize' => '5M',
            'URL' => '/storage'
        ]
    ],

    'auth' => [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],
        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => \Tanwencn\Admin\Database\Eloquent\User::class
            ],
        ]
    ],

    'middleware' => [
        'web',
        \Tanwencn\Admin\Http\Middleware\Authenticate::class,
        \Tanwencn\Admin\Http\Middleware\AdminMenu::class,
        \Tanwencn\Admin\Http\Middleware\FilterIfPjax::class,
        \Tanwencn\Admin\Http\Middleware\HttpLog::class
    ],

    'logger' => [
        'method' => ['post', 'put', 'patch', 'delete'],
        'except' => ['password', 'password_confirmation']
    ]
];
