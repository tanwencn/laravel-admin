<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/8 下午5:06
 */

namespace Tanwencn\Admin\Foundation;

use InvalidArgumentException;
use Illuminate\Auth\AuthManager;
use Tanwencn\Admin\Foundation\Menus\Item;

class Menu
{
    private $items;

    private $auth;

    private $uri_prefix;

    /**
     * Menu constructor.
     * @param AuthManager $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
        $this->uri_prefix = config('admin.route.prefix', 'admin');
    }

    public function new($title){
        $item = new Item($title);
        $this->items[] = $item;
        return $item;
    }

    /**
     * define menu
     * @param $name
     * @param array $parameters
     * @param null|array|string $parent
     * @param null|array $children
     */
    public function define($name, $parameters = [], $parent = null, $children = [])
    {
        if (is_array($parent)) {
            $children = $parent;
            $parent = null;
        }
        if ($parent) {
            if (!array_has($this->items, $parent))
                throw new InvalidArgumentException("{$parent} does not exist");

            $name = "{$parent}.children.{$name}";

            if (empty($parameters['icon'])) $parameters['icon'] = 'fa-circle-o';
        } else {
            if (empty($parameters['icon'])) $parameters['icon'] = '';
        }
        if (empty($parameters['sort'])) $parameters['sort'] = 10;

        array_set($this->items, $name, $parameters);

        foreach ($children as $childName => $child) {
            $this->define($childName, $child, $name);
        }
    }

    /**
     * Menu parser
     * @param $items
     * @return static
     */
    protected function parser($items)
    {
        return collect($items)->sortBy('sort')->map(function ($val) {
            $val->children = array_filter($this->parser($val->children)->toArray());

            return $val;

        })->filter(function ($val) {
            if(empty($val->children) && starts_with($val->url, 'javascript')) return false;

            $user = $this->auth->user();

            return empty($val->authority) || $user->can(trim($val->authority));

        });
    }

    /**
     * render
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        return view('admin::menu', [
            'items' => $this->parser($this->items)
        ]);
    }
}