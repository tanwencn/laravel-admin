<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2019/10/19 10:42 AM
 */

namespace Tanwencn\Admin\Http\Controllers;


use Illuminate\Support\Str;

trait Package
{
    protected function view($view, $data = [], $mergData = [])
    {
        if (strpos($view, '/') === 0)
            return Admin::view($view, $data, $mergData);
        else
            return view('admin::_'.Str::plural(Str::before(Str::snake(class_basename(static::class)), '_controller')) . '.' . $view, $data, $mergData);
    }
}