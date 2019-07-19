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

class RanksCollection extends Collection
{
    protected $ranks = [];

    public function __construct($items = [])
    {
        parent::__construct($items);
        $this->ranks = $this->pluck('meta_value', 'meta_key');
    }

    public function getRanks($key = ''){
        if($key)
            return $this->ranks->get($key);
        else
            $this->ranks;
    }
}