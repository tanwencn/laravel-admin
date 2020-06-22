<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/16 15:57
 */

namespace Tanwencn\Admin\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tanwencn\Admin\Database\Scopes\LatestScope;

class OperationLog extends Model
{
    protected $fillable = ['method', 'uri', 'body', 'ip', 'user_id', 'status'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestScope());
    }

    public function user(){
        return $this->hasOne(config('admin.auth.providers.admin.model'), 'id', 'user_id');
    }
}