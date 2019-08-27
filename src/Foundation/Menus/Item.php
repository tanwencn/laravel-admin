<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/8 下午5:06
 */

namespace Tanwencn\Admin\Foundation\Menus;

use InvalidArgumentException;
use Illuminate\Auth\AuthManager;
use function PHPSTORM_META\type;

class Item
{
    public $title;
    public $sort;
    public $url;
    public $children;
    public $authority;

    public function __construct($title)
    {
        $this->title = $title;
        $this->sort = 10;
        $this->url = 'javascript:void(0);';
    }

    public function sort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        $this->url = app('url')->route($name, $parameters, $absolute);
        return $this;
    }

    public function uri($uri)
    {
        $this->url = url(config('admin.route.prefix', 'admin') . '/' . $uri);
        return $this;
    }

    public function child($title, \Closure $closure = null)
    {
        if(!str_contains($title, '<i'))
            $title = '<i class="fa fa-circle-o"></i><span>'.$title.'</span>';

        $item = new static($title);
        if (!is_null($closure))
            $closure($item);
        $this->children[] = $item;
        return $this;
    }

    public function auth($authority)
    {
        $this->authority = $authority;
        return $this;
    }
}