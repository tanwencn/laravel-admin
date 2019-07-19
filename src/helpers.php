<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2017/10/12 11:02
 */

use Tanwencn\Admin\Database\Eloquent;
use Tanwencn\Admin\Database\Collection\RecursiveCollection;

if (!function_exists('option')) {
    function option($name, $default = '')
    {
        return Eloquent\Option::findByName($name, $default);
    }
}

if (!function_exists('catpos')) {
    function catpos($category)
    {
        $results = [];

        if(!empty($category)) {

            if (!empty($category->hasParent())) {
                $results = array_merge($results, catpos($category->parent));
            }

            $results[] = $category;

        }

        return $results;
    }
}
