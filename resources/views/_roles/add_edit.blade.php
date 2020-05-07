@extends('admin::_layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_role':'add_role'), 1))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ Admin::action('index') }}"> {{ trans('admin.role') }}</a></li> @endsection

@section('content')
    <form action="{{ Admin::action('form', $model->id) }}"
          method="POST">
    {{ csrf_field() }}
    @if(isset($model->id))
        {{ method_field("PUT") }}
    @endif
    <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('name')?"has-error":"" }}">
                            <label class="col-form-label text-right col-form-label-sm col-md-4 text-right asterisk">{{ trans('admin.name') }}
                                ：</label>
                            <div class="col-md-8">
                                <input {{ $model->name=='superadmin'?'readonly':'' }} type="text" name="name"
                                       class="form-control form-control-sm @if($errors->has('name')) is-invalid @endif"
                                       value="{{ old('name', $model->name)}}">
                                    <span class="error invalid-feedback">{{$errors->first('name')}}</span>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('guard')?"has-error":"" }}">
                            <label class="col-form-label text-right col-form-label-sm col-md-4 text-right asterisk">{{ trans('admin.guard') }}
                                ：</label>
                            <div class="col-md-8">
                                <select data-minimum-results-for-search="Infinity" name="guard"
                                        class="form-control form-control-sm select2 @if($errors->has('guard')) is-invalid @endif">
                                    <option>请选择</option>
                                    @foreach($guards as $guard)
                                        <option @if(old('guard_name', $model->guard_name)==$guard) selected @endif>{{ $guard }}</option>
                                    @endforeach
                                </select>
                                    <span class="error invalid-feedback">{{$errors->first('guard')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="float-right btn btn-primary"
                                style="width: 120px">{{ trans('admin.save') }}</button>
                    </div>
                </div>
            </div>
            <!-- end panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ trans_choice('admin.permission', 1) }}
                        <div class="card-tools">
                            <form id="search" action="http://admin6.test/admin/permissions">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="search" class="form-control float-right" value="" placeholder="搜索...">


                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                            <div class="col-md-12 data-list p-0"></div>
                            @foreach($guards as $guard)
                                <template class="{{ $guard }}">
                                    <table class="table text-nowrap table-striped permissions no-data">
                                        <thead>
                                        <th width="80"><input type="checkbox"
                                                              class="grid-select-all checkbox-style"></th>
                                        <th>{{ trans_choice('admin.permission', 0) }}</th>
                                        <th>{{ trans('admin.guard') }}</th>
                                        </thead>
                                        <tbody>
                                        @isset($permissions_group[$guard])
                                            @foreach($permissions_group[$guard] as $permission)
                                                <tr>
                                                    <td>
                                                        @if($guard == 'admin' && auth()->user()->can($permission->name))
                                                            <input type="checkbox" name="permissions[]"
                                                                   {{ in_array($permission->id, $current_permissions)?'checked':'' }} class="grid-row-checkbox checkbox-style"
                                                                   value="{{ $permission->id }}">
                                                        @endif
                                                    </td>
                                                    <td>{{ \Illuminate\Support\Str::after(trans("{$permission->guard_name}.{$permission->name}"), '.') }}</td>
                                                    <td>{{ $permission->guard_name }}</td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                        </tbody>
                                    </table>
                                </template>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end row -->

    <!-- end #content -->
    <script>
        Admin.boot(function () {
            var table;

            $('[name="search"]').keyup(function () {
                if ($(this).prop('comStart')) return;    // 中文输入过程中不截断

                NProgress.start();
                table.search($('input[name="search"]').val()).draw();
                NProgress.done();
            }).on('compositionstart', function () {
                $(this).prop('comStart', true);
            }).on('compositionend', function () {
                $(this).prop('comStart', false);
            });

            $('[name="guard"]').change(function () {
                var name = $(this).val();
                $('.data-list').html($('template.' + name).html());
                table = $('.data-list>table').DataTable({searching: true, dom: 'Brtip',
                    buttons: [
                    'print'
                ]});
                $('.checkbox-style').iCheck({
                    checkboxClass: 'icheckbox_flat-red',
                    increaseArea: '10%' // optional
                });
                Admin.icheckEvent();
            });
            $('[name="guard"]').trigger('change');
        });
    </script>
@endsection
