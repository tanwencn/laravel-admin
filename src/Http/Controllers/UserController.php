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
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Tanwencn\Admin\Database\Eloquent\User;
use Tanwencn\Admin\Database\RelationHelper;
use Tanwencn\Admin\Facades\Admin;

class UserController extends Controller
{
    use ValidatesRequests;

    public function index(Request $request)
    {
        $model = User::with('roles')/*->whereHas('metas', function ($query) {
            $query->where('meta_key', 'is_administrator');
        })*/
        ;

        $search = $request->query('search');
        if (!empty($search)) {
            $model->where(function ($query) use ($search) {
                $query->where('email', 'like', "%{$search}%");
                $query->orWhere('name', 'like', "%{$search}%");
            });
        }

        $results = $model->paginate();

        return $this->view('index', compact('results'));
    }

    public function create()
    {
        return $this->_form(new User());
    }

    protected function _form(User $model)
    {
        $model->load('metas');
        $roles = Role::all()->pluck('name', 'name');

        return $this->view('add_edit', compact('model', 'roles'));
    }

    public function edit($id)
    {
        if (Auth::id() != $id)
            $this->authorize('edit_user');

        $model = User::with('roles')->findOrFail($id);

        return $this->_form($model);
    }

    public function store()
    {
        $model = new User();
        return $this->save($model, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($model->id)
            ],
            //'role' => 'required',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    public function update($id)
    {
        if (Auth::id() != $id)
            $this->authorize('edit_user');

        $model = User::findOrFail($id);
        return $this->save($model, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($model->id)
            ],
            //'role' => 'required',
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed'
        ]);
    }

    protected function save(User $model, $validates)
    {
        $request = request();

        $this->validate($request, $validates);

        $input = array_filter($request->except('role'));

        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $roles = array_filter($request->input('role', []));

        RelationHelper::boot($model)->save($input, function ($model) use ($roles) {
            if(!empty($roles)) {
                if (Auth::user()->hasRole('superadmin'))
                    $model->syncRoles($roles);
            }
        });

        $url = Auth::user()->can('view_user') ? Admin::action('index') : Admin::action('edit', $model->id);

        return redirect($url)->with('toastr_success', trans('admin.save_succeeded'));
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if ($id == 1) continue;
            $model = User::findOrFail($id);
            $model->delete();
        }

        return response([
            'status' => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }

    protected function abilitiesMap()
    {
        return array_merge(parent::abilitiesMap(), [
            'edit' => null,
            'update' => null,
        ]);
    }


}
