<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace App\Admin\Controllers;

use Tanwencn\Admin\Facades\Admin;
use Tanwencn\Admin\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function dashboard(){
        $this->authorize('dashboard');
        return Admin::view('index.dashboard');
    }

}
