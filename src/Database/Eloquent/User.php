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
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tanwencn\Admin\Facades\Admin;

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
        /*static::creating(function ($model) {
            if(!$model->password) $model->password = config('admin.auth.login.default_password', '123456');
        });*/
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getAvatarAttribute()
    {
        return $this->getMetas('avatar') ?: asset('/vendor/laravel-admin/logo.png');
    }

    public function getMorphClass()
    {
        return self::class;
    }

    public function getGuardNameAttribute()
    {
        return $this->guard_name;
    }

    public function getLastLoginTimeAttribute()
    {
        $operation = $this->operation()->where('uri', route('admin.login', [], false))->where('method', 'POST')->first();
        return $operation ? $operation->created_at : null;
    }

    public function operation()
    {
        return $this->hasMany('Tanwencn\Admin\Database\Eloquent\OperationLog', 'user_id', 'id');
    }

    /*permission cache bug
     * public function getPermissionsAttribute() {
        $permissions = Cache::rememberForever('permissions_cache', function() {
            return Permission::select('permissions.*', 'model_has_permissions.*')
                ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->get();
        });

        return $permissions->where('model_id', $this->id);
    }

    public function getRolesAttribute() {
        $roles = Cache::rememberForever('roles_cache', function () {
            return Role::select('roles.*', 'model_has_roles.*')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->get();
        });

        return $roles->where('model_id', $this->id);
    }*/
}