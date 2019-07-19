<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/2/9 13:20
 */

namespace Tanwencn\Admin\Database\Collection;

use Illuminate\Database\Eloquent\Collection;

class RecursiveCollection extends Collection
{
    protected function recursiveParser($key, $values, $level = 1, $closed_label = 0, \Closure $format = null)
    {
        $results = [];
        $values = is_callable($format)?$format($values):array_values($values);

        foreach ($values as $i => $value) {
            $value['level'] = $level;

            $value['start_label'] = intval($i == 0);

            if (!isset($values[$i + 1])) $closed_label++;

            $value['closed_label'] = !empty($value[$key]) && is_array($value[$key]) ? 0 : $closed_label;
            $results[] = $value;
            if (!empty($value[$key]) && is_array($value[$key])) {
                $results = array_merge($results, $this->recursiveParser($key, $value[$key], $level + 1, $closed_label));
                unset($value[$key]);
            }
        }

        return $results;
    }

    public function recursive($key = null, \Closure $format = null)
    {
        return $this->recursiveParser($key ?: "children", $this->toArray(), 1, 0, $format);
    }
}