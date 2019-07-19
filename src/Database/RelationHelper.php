<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Database;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tanwencn\Admin\Database\Eloquent\Datas\Terms;

class RelationHelper
{
    protected $model;

    protected $fileds;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->fileds = Schema::getColumnListing($this->model->getTable());
    }

    public static function boot(Model $model)
    {
        return new static($model);
    }

    protected function prepareFileds($data)
    {
        $fileds = $this->fileds;
        return array_filter($data, function ($val, $column) use ($fileds) {
            return !is_null($val) && $val !== false && in_array($column, $fileds);
        }, ARRAY_FILTER_USE_BOTH);
    }

    protected function prepareRelation($data)
    {
        $fileds = $this->fileds;
        $model = $this->model;
        return array_filter($data, function ($val, $column) use ($fileds, $model) {
            return !empty($val) && !in_array($column, $fileds) && method_exists($model, $column) && $relation = call_user_func([$this->model, $column]) && $relation = call_user_func([$this->model, $column]);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function save($data = null, \Closure $transaction = null)
    {
        $data = ($data) ?: request()->all();

        DB::transaction(function () use ($data, $transaction) {
            if (method_exists($this->model, 'trashed')) {
                if ($this->model->trashed()) {
                    $this->model->restore();
                }
            }

            $this->model->fill($this->prepareFileds($data));

            $this->model->save();

            $this->updateRelation($this->prepareRelation($data));

            if ($transaction) $transaction($this->model);
        });
    }


    protected function updateRelation($relationsData)
    {
        foreach ($relationsData as $name => $values) {
            if (!method_exists($this->model, $name)) {
                continue;
            }

            $relation = $this->getRelation($name);

            $formatKey = 'relationFormat' . ucwords($name);
            if (method_exists($this->model, $formatKey)) {
                $values = call_user_func([$this->model, $formatKey], $values);
            }

            /*$oneToOneRelation = $relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne
                || $relation instanceof \Illuminate\Database\Eloquent\Relations\MorphOne;

            $prepared = $this->prepareUpdate([$name => $values], $oneToOneRelation);

            if (empty($prepared)) {
                continue;
            }*/

            switch (get_class($relation)) {
                case \Illuminate\Database\Eloquent\Relations\BelongsToMany::class:
                case \Illuminate\Database\Eloquent\Relations\MorphToMany::class:
                    $traits = class_uses_recursive($relation->getModel());
                    if(in_array(Terms::class, $traits)){
                        $change = $relation->wherePivotIn('term_id', $relation->get()->pluck('id'))->sync(array_filter($values));
                    } else {
                        $change = $relation->sync(array_filter($values));
                    }

                    if (array_filter($change)) {
                        $this->model->touch();
                    }
                    break;
                case \Illuminate\Database\Eloquent\Relations\HasOne::class:
                    var_dump($name);
                    var_dump($relationsData[$name]);
                    exit;
                /*$related = $this->model->$name;

                // if related is empty
                if (is_null($related)) {
                    $related = $relation->getRelated();
                    $related->{$relation->getForeignKeyName()} = $this->model->{$this->model->getKeyName()};
                }

                foreach ($prepared[$name] as $column => $value) {
                    $related->setAttribute($column, $value);
                }

                $related->save();
                break;
            case \Illuminate\Database\Eloquent\Relations\MorphOne::class:
                $related = $this->model->$name;
                if (is_null($related)) {
                    $related = $relation->make();
                }
                foreach ($prepared[$name] as $column => $value) {
                    $related->setAttribute($column, $value);
                }
                $related->save();
                break;*/
                case \Illuminate\Database\Eloquent\Relations\HasMany::class:
                case \Illuminate\Database\Eloquent\Relations\MorphMany::class:

                    $keyName = $relation->getRelated()->relation_key ?: $relation->getRelated()->getKeyName();

                    $keys = [];

                    foreach ($values as $related) {
                        $replicate = $this->getRelation($name);
                        /*$foreigKeyName = $relation->getForeignKeyName();
                        $current = $relation->getRelated();
                        $current->$foreigKeyName = $this->model->getKey();*/
                        if(!empty($oldkey = array_only($related, $keyName))){
                            $instance = $replicate->firstOrNew($oldkey);
                        }else{
                            $instance = $replicate->make();
                            /*$instance = $replicate->getRelated()->newInstance();
                            $instance->setAttribute($replicate->getForeignKeyName(), $replicate->getParentKey());*/
                        }

                        static::boot($instance)->save($related);
                        $keys[] = $instance->$keyName;
                    }

                    $deletes = $relation->whereNotIn($keyName, $keys)->get();

                    $deletes->transform(function ($item, $key) {
                        $item->delete();
                        return $item;
                    });

                    break;
            }
        }
    }

    protected function getRelation($name)
    {
        $relation = $this->model->$name();
        if (method_exists($relation->getModel(), 'trashed')) {
            $relation->withTrashed();
        }
        return $relation;
    }
}