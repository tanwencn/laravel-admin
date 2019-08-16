<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/3/7 10:56
 */

namespace Tanwencn\Admin\Database\Eloquent;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tanwencn\Admin\Database\Eloquent\Concerns\HasMetas;

class User extends Authenticatable
{
    use HasRoles, HasMetas, Notifiable;

    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if(!$model->password) $model->password = config('admin.user.default_password');
        });
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }

            $model->metas()->delete();
        });
    }

    public function getNameAttribute($value)
    {
        return $value ?: $this->email;
    }

    public function setPasswordAttribute($value){
        return bcrypt($value);
    }

    public function metas()
    {
        return $this->hasMany(UserMeta::class, 'target_id');
    }

    public function getAvatarAttribute()
    {
        return $this->getMetas('avatar') ?: asset('/vendor/laravel-admin/logo.png');
    }

    public function getMorphClass()
    {
        return self::class;
    }

}