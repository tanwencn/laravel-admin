<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Middleware;

use Closure;
use Tanwencn\Admin\Facades\Admin;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Admin::menu()->new(trans_choice('admin.dashboard', 0))->icon('tachometer-alt')->uri('/')->auth('dashboard');

        Admin::menu()->new(trans_choice('admin.laravel_logs', 0))->icon('record-vinyl')->uri('logs')->auth('laravel_logs')->sort(100);

        Admin::menu()->new(trans_choice('admin.role', 0))->icon('users')->uri('roles')->auth('view_role');

        Admin::menu()->new(trans_choice('admin.permission', 0))->icon('user-lock')->uri('permissions')->auth('view_permission');

        Admin::menu()->new(trans_choice('admin.user', 0))->icon('user')->uri('users')->auth('view_user');

        Admin::menu()->new(trans_choice('admin.setting', 0))->icon('cog')->uri('options/general')->sort(98)->auth('general_settings');

        Admin::menu()->new(trans_choice('admin.operationlog', 0))->icon('crosshairs')->sort(99)->route('admin.operationlog')->auth('operationlog');

        return $next($request);
    }
}
