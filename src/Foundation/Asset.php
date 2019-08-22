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
    protected $head;

    protected $footer;

    public function __call($name, $arguments)
    {
        if (in_array($name, ['head', 'footer'])) {
            if (empty($arguments)) return $this->parserAsset($this->{$name});
        } else {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }
    }

    public function add($path, $position = 100, $type = null)
    {
        $path = trim($path);
        if (!$type)
            $type = ends_with($path, '.css') ? 'head' : 'footer';

        $this->{$type}[] = [
            'url' => $this->parserPath($path),
            'position' => $position
        ];
        return $this;
    }

    public function addBag($path)
    {
        $cssPath = str_ireplace('{type}', 'css', $path);
        $jsPath = str_ireplace('{type}', 'js', $path);
        return $this->add($cssPath . '.css')->add($jsPath . '.js');
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