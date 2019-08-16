<?php
/**
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/5/30 下午5:53
 */
namespace Tanwencn\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Tanwencn\Admin\Foundation\Asset asset
 * @method static \Tanwencn\Admin\Foundation\Menu menu
 * @method static \Tanwencn\Admin\Foundation\Table table
 * @method static \Tanwencn\Admin\Foundation\Side side
 * @method static object user
 * @method static \Illuminate\Routing\Route router
 * @method static \Illuminate\View\View view
 * @method static void dashboard
 */

class Admin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin';
    }
}