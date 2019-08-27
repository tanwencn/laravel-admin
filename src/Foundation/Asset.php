<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/15 下午2:59
 */

namespace Tanwencn\Admin\Foundation;

class Asset
{
    protected $before;

    protected $head;

    protected $footer;

    protected $after;

    protected $position;

    public function __construct()
    {
        $this->position = 0;
    }

    public function head()
    {
        return $this->parserAsset($this->head) . $this->parserAsset($this->before);
    }

    public function footer()
    {
        return $this->parserAsset($this->footer) . $this->parserAsset($this->after);
    }

    public function before($path, $position = null)
    {
        return $this->add($path, $position, 'before');
    }

    public function after($path, $position = null)
    {
        return $this->add($path, $position, 'after');
    }

    public function add($path, $position = null, $type = null)
    {
        if (empty($path)) return $this;

        if (!$position)
            $position = $this->position;

        $path = trim($path);
        if (!$type)
            $type = ends_with($path, '.css') ? 'head' : 'footer';

        $this->{$type}[] = [
            'url' => $this->parserPath($path),
            'position' => $position
        ];
        $this->position += 10;
        return $this;
    }

    public function addBag($path, $position = null, $type = null)
    {
        return $this->add(str_ireplace('{type}', 'css', $path) . '.css', $position, $type)->add(str_ireplace('{type}', 'js', $path) . '.js', $position, $type);
    }

    protected function parserPath($path)
    {
        if (starts_with($path, ['http:', 'https:', '//'])) {
            return $path;
        }

        return asset($path);
    }

    protected function parserAsset($itmes)
    {
        if (empty($itmes)) return null;

        return collect($itmes)->sortBy('position')->map(function ($item) {
            if (ends_with($item['url'], '.css'))
                return '<link rel="stylesheet" href="' . $item['url'] . '">' . "\n";
            else
                return '<script src="' . $item['url'] . '"></script>' . "\n";

        })->implode('');
    }

    public function jsdelivrCombile(...$paths)
    {
        $css = [];
        $js = [];
        foreach ($paths as $path) {
            if (ends_with($path, '.css'))
                $css[] = $path;
            else
                $js[] = $path;
        }

        return $this->jsdelivr('combine/'.implode(',', $css))->jsdelivr('combine/'.implode(',', $js));
    }

    public function jsdelivr($path, $position = null, $type = null)
    {
        if ($path == 'combine/') return $this;

        return $this->add('https://cdn.jsdelivr.net/' . $path, $position, $type);
    }

    public function jsdelivrBag($path, $position = null, $type = null)
    {
        return $this->jsdelivr(str_ireplace('{type}', 'css', $path) . '.css', $position, $type)->jsdelivr(str_ireplace('{type}', 'js', $path) . '.js', $position, $type);
    }
}