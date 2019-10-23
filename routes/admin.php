<?php

Route::prefix(config('admin.router.prefix', 'admin'))->middleware('web')->group(function ($router) {
    $router->get('login', config('admin.auth.login.controller').'@showLoginForm')->name('admin.login');
    $router->post('login', config('admin.auth.login.controller').'@login')->name('admin.login');
    $router->get('logout', config('admin.auth.login.controller').'@logout')->name('admin.logout');
});

Admin::router()->namespace('Tanwencn\Admin\Elfinder')->group(function ($router) {
        $router->any('elfinder/connector', ['as' => 'elfinder.connector', 'uses' => 'Controller@showConnector']);
        $router->get('elfinder/show', ['as' => 'admin.elfinder.show', 'uses' => 'Controller@showIndex']);
    });

Admin::router()->namespace('Tanwencn\Admin\Http\Controllers')->group(function ($router) {

        $router->get('/logs', 'LogViewController@index')->name('logs');

        $router->get('/operationlog', 'OperationLogController@index')->name('operationlog');

        $router->resource('users', 'UserController')->names('users');;

        $router->resource('roles', 'RoleController')->names('roles');

        $router->resource('permissions', 'PermissionController')->names('permissions');

        $router->get('options/general', 'OptionController@general')->name('options.general');

        $router->post('options/save', 'OptionController@save');

    });

if(file_exists(config('admin.router.routes'))) {
    Admin::router()->namespace(config('admin.router.namespaces'))->group(config('admin.router.routes'));
}
