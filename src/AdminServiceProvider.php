<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

namespace Tanwencn\Admin;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Tanwencn\Admin\Consoles\BootPermissionsCommand;
use Tanwencn\Admin\Consoles\BuildCommand;
use Tanwencn\Admin\Consoles\InstallCommand;
use Tanwencn\Admin\Consoles\ResetSuperAdminCommand;
use Tanwencn\Admin\Foundation\Admin;
use Tanwencn\Admin\Http\BootstrapComposer;
use Illuminate\Support\Facades\Blade;

class AdminServiceProvider extends ServiceProvider
{
    protected $middlewareGroup = [
        'web',
        'Tanwencn\Admin\Http\Middleware\Authenticate',
        'Tanwencn\Admin\Http\Middleware\Menu',
        'Tanwencn\Admin\Http\Middleware\HttpLog'
    ];

    public function boot(Gate $gate)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config' => config_path(),
                __DIR__ . '/../public' => public_path('vendor/laravel-admin'),
                __DIR__ . '/../resources/lang' => resource_path('lang'),
                __DIR__ . '/../database/migrations' => database_path('migrations')
            ], 'admin');

            $this->commands([
                InstallCommand::class,
                BuildCommand::class,
                BootPermissionsCommand::class,
                ResetSuperAdminCommand::class
            ]);
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');

        $this->loadViewsFrom(app_path('Admin/Views'), 'admin');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        /*Relation::morphMap([
            Page::class => Page2::class
        ]);*/

        $gate->before(function (Authorizable $user) {
            if (method_exists($user, 'hasRole')) {
                return $user->hasRole('superadmin') ?: null;
            }
        });


        View::composer(
            ['admin::*'],
            BootstrapComposer::class
        );

        Blade::component('admin::components.button-dropdown', 'admin_buttons_dropdown');
        Blade::component('admin::components.page', 'admin_page');
        Blade::component('admin::components.table', 'admin_table');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        config(Arr::dot(config('admin.elfinder', []), 'elfinder.roots.'));
        config(Arr::dot(Arr::except(config('admin.auth', []), 'login'), 'auth.'));

        $this->app->singleton('admin', function ($app) {
            return new Admin($app['config'], $app['auth'], $app['router']);
        });

        $middlewareGroup = array_merge($this->middlewareGroup, config('admin.router.middleware', []));
        $this->app['router']->middlewareGroup('admin', $middlewareGroup);
    }
}
