<?php

return [

    'layout' => [
        'body_class' => 'hold-transition sidebar-mini layout-fixed text-sm',
        'logo' => '&nbsp;&nbsp;&nbsp;<strong>Laravel</strong>&nbsp;&nbsp;<span class="brand-text font-weight-light">Admin</span>',
        'footer' => '<strong>Made by The <a href="http://www.tanecn.com" target="_blank">TaneCN</a>.</strong>'
    ],

    'router' => [
        'prefix' => 'admin',
        'namespaces' => 'App\\Admin\\Controllers',
        'routes' => app_path('Admin/routes.php'),
        'index' => 'admin.dashboard',
        'middleware' => [
            'web',
            'Tanwencn\Admin\Http\Middleware\Authenticate',
            'Tanwencn\Admin\Http\Middleware\Menu',
            'Tanwencn\Admin\Http\Middleware\Pjax',
            'Tanwencn\Admin\Http\Middleware\HttpLog'
        ]
    ],

    'elfinder' => [
        'default' => [
            'process' => 'Tanwencn\Admin\FinderProcess',
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
        'login' => [
            'controller' => 'Tanwencn\Admin\Http\Controllers\LoginController',
            'username' => 'name'
        ],
        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ],
        ],
        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model' => Tanwencn\Admin\Database\Eloquent\User::class
            ],
        ]
    ],

    'logger' => [
        'method' => ['post', 'put', 'patch', 'delete'],
        'except' => ['password', 'password_confirmation']
    ]
];
