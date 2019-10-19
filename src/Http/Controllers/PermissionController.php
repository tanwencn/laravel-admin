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
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Tanwencn\Admin\Facades\Admin;

class PermissionController extends Controller
{
    use ValidatesRequests,Package;

    public function __construct()
    {
        $this->guards = array_keys(config('auth.guards', []));
    }

    public function index(Request $request)
    {
        $model = Permission::query();
        $search = $request->query('search');
        if (!empty($search)) {
            $model->where(function ($query) use ($search) {
                $search = ['like', "%{$search}%"];
                return $query->where('name', ...$search)->orWhere('name', ...$search);
            });
        }

        $results = $model->paginate();

        return $this->view('index', compact('results'));
    }

    public function create()
    {
        return $this->_form(new Permission());
    }

    protected function _form(Permission $model)
    {
        $guards = $this->guards;

        return $this->view('add_edit', compact('model', 'guards'));
    }

    public function edit($id)
    {
        $model = Permission::findOrFail($id);

        return $this->_form($model);
    }

    public function store()
    {
        return $this->save(new Permission());
    }

    public function update($id)
    {
        $permission = Permission::findOrfail($id);

        return $this->save($permission);
    }

    protected function save(Permission $model)
    {
        $request = request();

        $this->validate($request, [
            'name' => 'required|max:255',
            'guard' => ['required', Rule::in($this->guards)]
        ]);

        $model->name = $request->input('name');

        $model->guard_name = $request->input('guard');

        $model->save();

        return redirect(Admin::action('index'))->with('toastr_success', trans('admin.save_succeeded'));
    }

    public function destroy($id, Request $request)
    {
        $ids = $id ? [$id] : $request->input('ids');
        foreach ($ids as $id) {
            $model = Permission::findOrFail($id);
            $model->delete();
        }

        return response([
            'status' => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }

    protected function abilitiesMap()
    {
        return [
            'index' => 'view_permission',
            'edit' => 'edit_permission',
            'update' => 'edit_permission',
            'create' => 'add_permission',
            'store' => 'add_permission',
            'destroy' => 'delete_permission'
        ];
    }


}
