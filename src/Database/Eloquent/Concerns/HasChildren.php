<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/14 16:59
 */

namespace Tanwencn\Admin\Database\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Model;
use Tanwencn\Admin\Database\Collection\RecursiveCollection;

trait HasChildren
{
    public static function bootHasChildren()
    {
        static::registerModelEvent('init', function ($model) {
            $model->setAppends(array_merge($model->appends, ['original']));
        });

        static::deleting(function (Model $model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }

            $model->children()->forceDelete();
        });
    }

    public function newCollection(array $models = [])
    {
        return new RecursiveCollection($models);
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id')->with('children');
    }

    public function hasParent()
    {
        return !empty($this->parent);
    }

    public function hasChildren()
    {
        return empty($this->children) || $this->children->isNotEmpty();
    }

    public function scopeTree($query, $parent_id = 0, $columns = [])
    {
        if (empty($columns)) {
            $columns = $this->tree_filed ?: ['id', 'parent_id', 'title'];
        }

        return $query->where('parent_id', $parent_id)->select($columns)->with(['children' => function ($query) use ($columns) {
            $query->select($columns);
        }]);
    }

    public static function saveOrder($items, $parent_id = 0)
    {
        foreach ($items as $key => $item) {
            $model = static::findOrFail($item['id']);
            $model->order = $key;
            $model->parent_id = $parent_id;
            $model->save();

            if (!empty($item['children']))
                static::saveOrder($item['children'], $model->id);
        }
    }

    public function getOriginalAttribute()
    {
        return $this->getOriginal();
    }
}