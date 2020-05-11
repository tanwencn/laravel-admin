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
use Illuminate\Support\Facades\View;
use Tanwencn\Admin\Database\Eloquent\Option;
use Tanwencn\Admin\Facades\Admin;

class OptionController extends Controller
{
    use AuthorizesRequests,Package;

    public function view($template){
        $this->authorize('admin.setting');
        
        abort_unless(View::exists("admin::options.{$template}"), 404);
        
        $view = function($template){
            return route('admin.options', compact('template'));
        };

        return view("admin::_options.view", compact('template', 'view'));
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

        return redirect(url()->previous())->with('success', trans('admin.save_succeeded'));
    }

}
