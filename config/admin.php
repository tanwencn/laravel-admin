<?php

return [

    'user' => [
        'username' => 'name',
        'default_password' => '123456'
    ],

    'layout' => [
        'body_class' => 'hold-transition skin-black sidebar-mini',
        'logo' => '<strong>Laravel</strong> Admin',
        'logo_mini' => '<strong>L</strong> A',
        'footer' => '<strong>Made by The <a href="http://www.tanecn.com" target="_blank">TaneCN</a>.</strong>'
    ],

    'router' => [
        'prefix' => 'admin',
        'namespaces' => 'App\\Admin\\Controllers',
        'routes' => app_path('Admin/routes.php'),
        'index' => 'admin.dashboard',
        'middleware' => [
            'web',
            Tanwencn\Admin\Http\Middleware\Authenticate::class,
            Tanwencn\Admin\Http\Middleware\Menu::class,
            Tanwencn\Admin\Http\Middleware\Asset::class,
            Tanwencn\Admin\Http\Middleware\Pjax::class,
            Tanwencn\Admin\Http\Middleware\HttpLog::class
        ]
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

    'logger' => [
        'method' => ['post', 'put', 'patch', 'delete'],
        'except' => ['password', 'password_confirmation']
    ],

    'laravel_logs' => [
        'read_once_rows' => 30000
    ]
];
