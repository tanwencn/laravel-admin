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
        $this->add(trans_choice('admin.user_group', 0))->icon('user-lock')->child(trans_choice('admin.role', 0), function($menu){
            $menu->uri('roles')->auth('view_role');
        })->child(trans_choice('admin.permission', 0), function($menu){
            $menu->uri('permissions')->auth('view_permission');
        })->child(trans_choice('admin.user', 0), function($menu){
            $menu->uri('users')->auth('view_user');
        });

        $this->group(trans_choice('admin.system', 0), 99)->add(trans_choice('admin.log', 0))->sort(99)->icon('record-vinyl')->child(trans_choice('admin.laravel_logs', 0), function($menu){
            $menu->uri('logs')->auth('laravel_logs')->sort(100);
        })->child(trans_choice('admin.operationlog', 0), function($menu){
            $menu->route('admin.operationlog')->auth('operationlog')->sort(99);
        });

        $this->group(trans_choice('admin.system', 0))->add(trans_choice('admin.setting', 0))->icon('cog')->uri('options/general')->sort(98)->auth('general_settings');
    }
}
