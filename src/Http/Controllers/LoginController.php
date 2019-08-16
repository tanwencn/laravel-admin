<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers, ValidatesRequests;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function($request, $next){
            if($this->guard()->check()){
                return redirect($this->redirectPath());
            }
            return $next($request);
        })->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin::auth.login')->with('username', $this->username());
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect($this->redirectPath());
    }

    protected function redirectTo()
    {
        return '/'.config('admin.route.prefix', 'admin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function username()
    {
        return config('admin.user.username', 'email');
    }
}
