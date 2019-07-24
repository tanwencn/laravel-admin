<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Controllers;

use Tanwencn\Admin\Facades\Admin;

class DashboardController extends Controller
{
    public function index(){
        $this->authorize('dashboard');
        return Admin::view('dashboard.index');
    }

}
