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

class AdminMenu
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
        Admin::menu()->new('<i class="fa fa-dashboard"></i> <span>'.trans_choice('admin.dashboard', 0).'</span>')->uri('/')->auth('dashboard');

        Admin::menu()->new('<i class="fa fa-lock"></i> <span>'.trans_choice('admin.role', 0).'</span>')->uri('roles')->auth('view_role');

        Admin::menu()->new('<i class="fa fa-user"></i> <span>'.trans_choice('admin.user', 0).'</span>')->uri('users')->auth('view_user');

        Admin::menu()->new('<i class="fa fa-cog"></i> <span>'.trans_choice('admin.setting', 0).'</span>')->uri('options/general')->auth('general_settings');

        Admin::menu()->new('<i class="fa fa-outdent"></i> <span>'.trans_choice('admin.operationlog', 0).'</span>')->sort(99)->route('admin.operationlog')->auth('operationlog');

        return $next($request);
    }
}
