<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/15 下午2:59
 */

namespace Tanwencn\Admin\Foundation;

use BadMethodCallException;

class Asset
{
    protected $css;

    protected $js;

    public function __call($name, $arguments)
    {
        if (in_array($name, ['css', 'js'])) {
            if (empty($arguments)) return $this->parserAsset($this->{$name});

            $this->{$name}[] = [
                'url' => $this->parserPath($arguments[0]),
                'postion' => isset($arguments[1]) ? $arguments : 100
            ];
        } else {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }
    }

    private function parserPath($path)
    {
        if (starts_with($path, ['http:', 'https:', '//'])) {
            return $path;
        }

        return asset($path);
    }

    private function parserAsset($itmes)
    {
        if (empty($itmes)) return null;

        return collect($itmes)->sortBy('position')->map(function ($item) {
            if (ends_with($item['url'], '.css')) {
                return '<link rel="stylesheet" href="' . $item['url'] . '">';
            } else {
                return '<script src="' . $item['url'] . '"></script>';
            }
        })->implode('');
    }
}