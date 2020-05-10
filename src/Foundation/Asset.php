<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/15 下午2:59
 */

namespace Tanwencn\Admin\Foundation;

use Illuminate\Support\Str;

class Asset
{
    protected $head;

    protected $footer;

    protected $items = [];

    protected $position = 10;

    public function header()
    {
        if(!$this->head) $this->head = new static();
        return $this->head;
    }

    public function footer()
    {
        if(!$this->footer) $this->footer = new static();
        return $this->footer;
    }

    public function render()
    {
        return $this->parserAsset($this->items);
    }

    public function add($path, $position = 10)
    {
        if (empty($path)) return $this;

        if (!$position)
            $position = $this->position;

        $path = trim($path);

        $this->items[] = [
            'url' => $this->parserPath($path),
            'position' => $position
        ];
        $this->position += 10;
        return $this;
    }

    protected function parserPath($path)
    {
        if (Str::startsWith($path, ['http:', 'https:', '//'])) {
            return $path;
        }

        return asset($path);
    }

    protected function parserAsset($itmes)
    {
        if (empty($itmes)) return "";

        return collect($itmes)->sortBy('position')->map(function ($item) {
            if (Str::endsWith($item['url'], '.css'))
                return '<link rel="stylesheet" href="' . $item['url'] . '">' . "\n";
            else
                return '<script src="' . $item['url'] . '"></script>' . "\n";

        })->implode('');
    }

    public function __toString()
    {
        return $this->render();
    }
}