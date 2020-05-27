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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Tanwencn\Admin\Facades\Admin;

class UserController extends Controller
{
    use ValidatesRequests, Package;

    protected $model;

    public function __construct()
    {
        $this->model = config('admin.auth.providers.admin.model');
        $fileds = ['email', 'name'];
        array_unshift($fileds, config('admin.auth.login.username', 'email'));
        View::share('user_name_fileds', array_filter(array_unique($fileds)));
    }

    public function index(Request $request)
    {
        $model = $this->model::with('roles');
        if (!Auth::user()->hasRole('superadmin'))
            $model->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'superadmin');
            });

        $search = $request->query('search');
        if (!empty($search)) {
            $model->where(function ($query) use ($search) {
                $query->where('email', 'like', "%{$search}%");
                $query->orWhere('name', 'like', "%{$search}%");
                $query->orWhereHas('metas', function ($build) use ($search) {
                    $build->where('meta_value', 'like', "%{$search}%");
                });
            });
        }

        $results = $model->paginate();

        return $this->view('index', compact('results'));
    }

    public function create()
    {
        return $this->_form(new $this->model());
    }

    protected function _form($model)
    {
        $model->load('metas');
        $role = Role::query();
        if (!Auth::user()->hasRole('superadmin'))
            $role->whereIn('name', Auth::user()->roles->pluck('name')->all());

        $roles = $role->get();
        
        return $this->view('add_edit', compact('model', 'roles'));
    }

    public function edit($id)
    {
        if (Auth::id() != $id)
            $this->authorize('edit_user');

        $model = $this->model::with('roles')->findOrFail($id);

        return $this->_form($model);
    }

    public function store()
    {
        $model = new $this->model();
        return $this->save($model);
    }

    public function update($id)
    {
        if (Auth::id() != $id)
            $this->authorize('edit_user');

        $model = $this->model::findOrFail($id);
        return $this->save($model);
    }

    protected function save($model)
    {
        $request = request()->replace(array_filter(request()->all()));

        $validates = [
            'email' => ['email', 'max:255'],
            'role' => 'required',
            'role.*' => function ($attribute, $value, $fail) {
                if (!Auth::user()->hasRole('superadmin') && !in_array($value, Auth::user()->roles->pluck('name')->all())) {
                    $fail($attribute.' is invalid.');
                }
            },
            'name' => ['max:255'],
            'password' => [Rule::requiredIf(!$model->id), 'min:6', 'confirmed']
        ];

        $login_filed = config('admin.auth.login.username', 'email');
        $validates[$login_filed] = array_merge($validates[$login_filed], ['required', Rule::unique('users')->ignore($model->id)]);

        $this->validate($request, $validates);

        $input = array_filter($request->except('role'));

        $roles = array_filter($request->input('role', []));

        $model->fill($input);

        $model->save();

        $model->saveMetas($input['metas']);

        if (!empty($roles) && Auth::user()->can('edit_role')) {
            if (!Auth::user()->hasRole('superadmin'))
                $roles = array_diff($roles, ['superadmin']);

            $model->syncRoles($roles);
        }

        $url = Auth::user()->can('view_user') ? Admin::action('index') : Admin::action('edit', $model->id);

        return redirect($url)->with('success', trans('admin.save_succeeded'));
    }

    public function destroy($id, Request $request)
    {
        $ids = $id ? [$id] : $request->input('ids');

        foreach ($ids as $id) {
            if ($id == 1) continue;
            $model = $this->model::findOrFail($id);
            $model->delete();
        }

        return response([
            'message' => trans('admin.delete_succeeded'),
        ]);
    }

    protected function abilitiesMap()
    {
        return [
            'index' => 'view_user',
            'create' => 'add_user',
            'store' => 'add_user',
            'destroy' => 'delete_user'
        ];
    }
}
