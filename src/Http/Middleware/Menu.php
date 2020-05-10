<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Middleware;

class Menu extends AbstractMenuMiddleware
{
    public function boot()
    {
        $this->add(trans_choice('admin.dashboard', 0))->icon('tachometer-alt')->uri('/')->auth('dashboard');

        $this->add(trans_choice('admin.role', 0))->icon('users')->uri('roles')->auth('view_role');

        $this->add(trans_choice('admin.permission', 0))->icon('user-lock')->uri('permissions')->auth('view_permission');

        $this->add(trans_choice('admin.user', 0))->icon('user')->uri('users')->auth('view_user');

        $this->group('系统', 99)->add(trans_choice('admin.laravel_logs', 0))->icon('record-vinyl')->uri('logs')->auth('laravel_logs')->sort(100);

        $this->group('系统')->add(trans_choice('admin.setting', 0))->icon('cog')->uri('options/general')->sort(98)->auth('general_settings');

        $this->group('系统')->add(trans_choice('admin.operationlog', 0))->icon('crosshairs')->sort(99)->route('admin.operationlog')->auth('operationlog');
    }
}
