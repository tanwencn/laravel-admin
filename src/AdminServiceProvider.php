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
use Tanwencn\Admin\Foundation\Admin;
use Tanwencn\Admin\Http\BootstrapComposer;

class AdminServiceProvider extends ServiceProvider
{

    public function boot(Gate $gate)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config' => config_path(),
                __DIR__ . '/../resources/assets' => public_path('vendor/laravel-admin'),
                __DIR__ . '/../resources/lang' => resource_path('lang'),
                __DIR__ . '/../database/migrations' => database_path('migrations')
            ], 'admin');

            $this->commands([
                InstallCommand::class,
                BuildCommand::class,
                BootPermissionsCommand::class
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
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        config(Arr::dot(config('admin.elfinder', []), 'elfinder.roots.'));
        config(Arr::dot(config('admin.auth', []), 'auth.'));

        $this->app->singleton('admin', function ($app) {
            return new Admin($app['config'], $app['auth'], $app['router']);
        });

        $this->app['router']->middlewareGroup('admin', config('admin.router.middleware', []));
    }
}
