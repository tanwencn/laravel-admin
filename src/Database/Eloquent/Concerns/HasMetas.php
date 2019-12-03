<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/14 16:59
 */

namespace Tanwencn\Admin\Database\Eloquent\Concerns;


use Illuminate\Database\Eloquent\Builder;
use Tanwencn\Admin\Database\Eloquent\UserMeta;

trait HasMetas
{
    protected $hasMetasClass;

    public function initializeHasMetas(){
        $this->hasMetasClass = file_exists(static::class.'Meta')?static::class.'Meta':UserMeta::class;
    }
    
    public function metas()
    {
        return $this->hasMany($this->hasMetasClass, 'target_id');
    }

    public function getMetas($key, $default = null)
    {
        return $this->metas->getRanks($key)?:$default;
    }

    public function saveMetas($data){
        $data = array_filter($data);
        foreach ($data as $key => $val){
            if(!isset($val['meta_key'])) {
                $val = ['meta_key' => $key, 'meta_value' => $val];
            }
            $this->metas()->updateOrCreate(['meta_key' => $val['meta_key']], $val);
        }
    }

    public function relationFormatMetas($data){
        $metas = [];
        $data = array_filter($data);
        foreach ($data as $key => $val){
            if(isset($val['meta_key'])) {
                $metas[] = $val;
            }else{
                $metas[] = ['meta_key' => $key, 'meta_value' => $val];
            }
        }
        return  $metas;
    }

    public function scopeOrderByMeta(Builder $query, $field)
    {
        $key = "{$this->getTable()}.{$this->getKeyName()}";
        $join_table = $this->metas()->getModel()->getTable();
        return $query->select($this->getTable().'.*')->leftJoin($join_table, function ($join)use($join_table, $key, $field) {
            $join->on($key, '=', "{$join_table}.target_id")
                ->where("{$join_table}.meta_key", '=', $field);
        })->orderBy("{$join_table}.meta_value");
    }
}