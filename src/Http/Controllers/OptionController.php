<?php
/**
 * http://www.tanecn.com
 * 作者: Tanwen
 * 邮箱: 361657055@qq.com
 * 所在地: 广东广州
 * 时间: 2018/4/13 15:55
 */

namespace Tanwencn\Admin\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Tanwencn\Admin\Database\Eloquent\Option;
use Tanwencn\Admin\Facades\Admin;

class OptionController extends Controller
{
    use AuthorizesRequests;

    public function general()
    {
        $this->authorize('admin.setting');

        return Admin::view('options.general');
    }

    public function save()
    {
        $this->authorize('admin.setting');

        $options = request('options');

        foreach ($options as $name => $option) {
            $model = Option::firstOrNew([
                'name' => $name
            ]);
            $model->value = $option ?: '';
            $model->save();
        }

        return response()->json([
            'status' => true,
            'message' => trans('admin.save_succeeded')
        ]);
    }

}
