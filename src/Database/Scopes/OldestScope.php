<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/14 14:16
 */

namespace Tanwencn\Admin\Database\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OldestScope implements Scope
{
    protected $column;

    public function __construct($column='id')
    {
        $this->column = $column;
    }

    public function apply(Builder $builder, Model $model)
    {
        return $builder->oldest($this->column);
    }
}