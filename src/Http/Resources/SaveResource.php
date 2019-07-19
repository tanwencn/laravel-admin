<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/6/7 下午6:48
 */

namespace Tanwencn\Admin\Http\Resources;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Tanwencn\Admin\Database\RelationHelper;
use Tanwencn\Admin\Facades\Admin;

trait SaveResource
{
    use ValidatesRequests;

    protected function save($model, $validates)
    {
        $request = request();
        $this->validate($request, $validates);

        RelationHelper::boot($model)->save();

        return redirect(Admin::action('index'))->with('toastr_success', trans('admin.save_succeeded'));
    }
}