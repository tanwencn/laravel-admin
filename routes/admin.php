<?php

Route::prefix(config('admin.route.prefix', 'admin'))->middleware('web')->namespace('Tanwencn\Admin\Http\Controllers')->group(function ($router) {
    $router->get('login', 'LoginController@showLoginForm')->name('admin.login');
    $router->post('login', 'LoginController@login')->name('admin.login');
    $router->get('logout', 'LoginController@logout')->name('admin.logout');
});

Admin::router()->namespace('Tanwencn\Admin\Elfinder')->group(function ($router) {
        $router->any('elfinder/connector', ['as' => 'elfinder.connector', 'uses' => 'ElfinderController@showConnector']);
        $router->get('elfinder/popup/{input_id}', ['as' => 'admin.elfinder.popup', 'uses' => 'ElfinderController@showPopup']);
    });

Admin::router()->namespace('Tanwencn\Admin\Http\Controllers')->group(function ($router) {

        $router->get('/', 'DashboardController@index')->name('admin.dashboard');

        $router->get('/operationlog', 'OperationLogController@index')->name('admin.operationlog');

        $router->resource('users', 'UserController')->names('admin.users');;

        $router->resource('roles', 'RoleController')->names('admin.roles');

        $router->get('options/general', 'OptionController@general')->name('admin.options.general');

        $router->post('options/save', 'OptionController@save');

    });

if(file_exists(config('admin.route.routes')))
    Admin::router()->namespace(config('admin.route.namespaces'))->group(config('admin.route.routes'));
