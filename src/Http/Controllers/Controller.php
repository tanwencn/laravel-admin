<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/7 下午7:13
 */

namespace Tanwencn\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tanwencn\Admin\Facades\Admin;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    protected function view($view, $data = [], $mergData = [])
    {
        return Admin::view(str_plural(str_before(snake_case(class_basename(static::class)), '_controller')) . '.' . $view, $data, $mergData);
    }

    public function callAction($method, $parameters)
    {
        $abilitys = $this->abilitiesMap();
        $ability = is_array($abilitys) ? array_get($abilitys, $method) : $abilitys;

        if ($ability) {
            $this->authorize($ability);
        }

        return parent::callAction($method, $parameters);
    }

    /**
     * @return array
     */
    protected function abilitiesMap()
    {
        return [];
    }
}