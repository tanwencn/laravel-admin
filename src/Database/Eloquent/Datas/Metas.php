<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/6 17:16
 */

namespace Tanwencn\Admin\Database\Eloquent\Datas;

use Illuminate\Support\Str;
use Tanwencn\Admin\Database\Collection\RanksCollection;

trait Metas
{
    public $relation_key = 'meta_key';

    protected function bootIfNotBooted()
    {
        parent::bootIfNotBooted();

        $this->timestamps = false;
        $this->setTouchedRelations(['base']);
        $this->fillable(['meta_key', 'meta_value']);
    }

    public function base()
    {
        return $this->belongsTo(Str::before(get_class($this), 'Meta'), 'target_id');
    }

    public function newCollection(array $models = [])
    {
        return new RanksCollection($models);
    }

    public function setMetaValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['meta_value'] = json_encode($value);
        } else {
            $this->attributes['meta_value'] = $value;
        }
    }
}