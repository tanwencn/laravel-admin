<?php

return [

    'name' => 'Laravel Admin',

    /*
     * Route configuration.
     */
    'route' => [
        'prefix' => 'admin',
        'namespaces' => 'App\\Admin\\Controllers',
        'routes' => app_path('routes/admin.php')
    ],

    'elfinder' => [
        'default' => [
            'process' => Tanwencn\Admin\FinderProcess::class,
            'options' => [
                'disk' => 'public',
                'uploadOverwrite' => false,
                'uploadMaxSize' => '3M',
                'onlyMimes' => ['image'],
                'uploadOrder' => ['allow'],
                'path' => 'images',
                'alias' => 'Gallery'
            ]
        ]
    ],

    'auth' => [
        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ],
        ],
        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model' => \Tanwencn\Admin\Database\Eloquent\User::class
            ],
        ]
    ],

    'middleware' => [
        'web',
        Tanwencn\Admin\Http\Middleware\Authenticate::class,
        Tanwencn\Admin\Http\Middleware\Menu::class,
        Tanwencn\Admin\Http\Middleware\Asset::class,
        Tanwencn\Admin\Http\Middleware\Pjax::class,
        Tanwencn\Admin\Http\Middleware\HttpLog::class
    ],

    'logger' => [
        'method' => ['post', 'put', 'patch', 'delete'],
        'except' => ['password', 'password_confirmation']
    ]
];
