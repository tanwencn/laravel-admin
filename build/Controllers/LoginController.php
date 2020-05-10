<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2020/4/27 8:05 PM
 */

namespace App\Admin\Controllers;
use Illuminate\Http\Request;
use Tanwencn\Admin\Http\Controllers\LoginController as Controller;

class LoginController extends Controller
{
    protected function validateLogin(Request $request)
    {
        parent::validateLogin($request);
    }

    public function showLoginForm()
    {
        return view('admin::login')->with('username', $this->username());
    }
}
