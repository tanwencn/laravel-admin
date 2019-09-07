<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2019/9/7 7:13 PM
 */
namespace Tanwencn\Admin;

use Illuminate\Support\Facades\Auth;
use Tanwencn\Elfinder\Interfaces\FinderAuth;
use Tanwencn\Elfinder\Interfaces\FinderOption;

class FinderProcess implements FinderAuth,FinderOption
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
    }

    public function auth(){
        return $this->user;
    }

    public function option($options):array {
        if(!$this->user->hasRole('superadmin'))
            $options['path'] = str_finish($options['path'], DIRECTORY_SEPARATOR).$this->user->id;

        return $options;
    }
}