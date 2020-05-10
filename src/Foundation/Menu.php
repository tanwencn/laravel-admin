<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/8 下午5:06
 */

namespace Tanwencn\Admin\Foundation;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use Tanwencn\Admin\Foundation\Menus\Item;
use Tanwencn\Admin\Facades\Admin as Facade;

class Menu
{
    protected $items;

    protected $auth;

    protected $uri_prefix;

    protected $sort = 10;

    protected $is_group;

    protected $groups = [];

    protected $groups_map = [];

    /**
     * Menu constructor.
     * @param AuthManager $auth
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
        $this->uri_prefix = config('admin.router.prefix', 'admin');
    }

    public function group($title, $sort = null)
    {
        if($this->is_group) return $this;
        if (!isset($this->groups_map[$title]) && is_null($sort)) $sort = 10;
        if ($sort) $this->groups_map[$title] = $sort;
        $this->groups[$title] = @$this->groups[$title] ?: new static($this->auth);
        $this->groups[$title]->is_group = true;
        return $this->groups[$title];
    }

    public function add($title)
    {
        $item = new Item($title, $this->sort);
        $this->sort++;
        $this->items[] = $item;
        return $item;
    }

    /**
     * Menu parser
     * @param $items
     * @return static
     */
    protected function parser($items)
    {
        return collect($items)->sortBy('sort')->map(function ($val) {
            $val->children = array_filter($this->parser($val->children));

            return $val;

        })->filter(function ($val) {
            if (empty($val->children) && Str::startsWith($val->url, 'javascript')) return false;

            $user = $this->auth->user();

            return empty($val->authority) || $user->can(trim($val->authority));

        })->all();
    }

    /**
     * render
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        return Facade::view('_menu', [
            'items' => $this->allItems()
        ]);
    }

    public function items()
    {
        return $this->parser($this->items);
    }

    public function allItems()
    {
        asort($this->groups_map);
        $items = $this->items();
        foreach ($this->groups_map as $group_name => $_) {
            $group_items = $this->groups[$group_name]->items();
            if (!empty($group_items)) {
                array_unshift($group_items, $group_name);
                $items = array_merge($items, $group_items);
            }
        }
        return $items;
    }
}