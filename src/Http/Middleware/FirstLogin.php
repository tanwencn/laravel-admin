<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Tanwencn\Admin\Database\Eloquent\OperationLog;
use Tanwencn\Admin\Facades\Admin;

class FirstLogin
{

    public function handle($request, Closure $next, $status = null)
    {
        if($this->hasFirstLogin() && Route::currentRouteName() != 'admin.users.change_password'){
            response()->view('admin::_users.change_password', [
                'tip' => trans('admin.change_password_tip')
            ])->send();
        }
        return $next($request);
    }

    protected function hasFirstLogin(){
        $operation = Admin::user()->operation()
            ->where('uri', route('admin.login', [], false))
            ->where('method', 'POST')
            ->orderBy('id')
            ->first();

        return !$operation || $operation->created_at > Admin::user()->updated_at;
    }
}
