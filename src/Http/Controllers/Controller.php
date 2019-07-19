<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/7 下午7:13
 */

namespace Tanwencn\Admin\Http\Controllers;

use Tanwencn\Admin\Facades\Admin;
use Tanwencn\Admin\Http\Requests\Authorizes;
use Illuminate\Contracts\Auth\Access\Gate;

class Controller extends \Illuminate\Routing\Controller
{
    use Authorizes;

    protected function view($view, $data = [], $mergData = [])
    {
        return Admin::view(str_plural(str_before(snake_case(class_basename(static::class)),'_controller')) . '.' . $view, $data, $mergData);
    }
}