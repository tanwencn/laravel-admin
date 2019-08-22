@extends('admin::layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_role':'add_role'), 1))

@section('breadcrumbs')
    <li><a href="{{ Admin::action('index') }}"> {{ trans('admin.role') }}</a></li> @endsection

@section('content')
    <form action="{{ Admin::action('form', $model) }}"
          method="POST">
    {{ csrf_field() }}
    @if(isset($model->id))
        {{ method_field("PUT") }}
    @endif
    <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <div class="form-horizontal">
                            <div class="form-group {{ $errors->has('name')?"has-error":"" }}">
                                <label class="control-label col-md-2">{{ trans('admin.name') }}：</label>
                                <div class="col-md-8">
                                    @if($errors->has('name'))
                                        <label class="control-label">
                                            <i class="fa fa-times-circle-o"></i>{{$errors->first('name')}}
                                        </label>
                                    @endif
                                    <input {{ $model->name=='superadmin'?'readonly':'' }} type="text" name="name"
                                           class="form-control"
                                           value="{{ old('name', $model->name)}}">
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('guard')?"has-error":"" }}">
                                <label class="control-label col-md-2">{{ trans('admin.guard') }}：</label>
                                <div class="col-md-8">
                                    @if($errors->has('guard'))
                                        <label class="control-label">
                                            <i class="fa fa-times-circle-o"></i>{{$errors->first('guard')}}
                                        </label>
                                    @endif
                                    <select data-minimum-results-for-search="Infinity" name="guard"
                                            class="form-control select2">
                                        <option>请选择</option>
                                        @foreach($guards as $guard)
                                            <option @if(old('guard_name', $model->guard_name)==$guard) selected @endif>{{ $guard }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('permissions')?"has-error":"" }}">
                                <label class="control-label col-md-2">{{ trans_choice('admin.permission', 1) }}
                                    ：</label>
                                <div class="col-md-8 data-list"></div>
                                @foreach($guards as $guard)
                                    <template class="{{ $guard }}">
                                        <table class="table table-condensed table-bordered permissions">
                                            <thead>
                                            <th width="80"><input type="checkbox" class="grid-select-all checkbox-style"></th>
                                            <th>{{ trans_choice('admin.permission', 0) }}</th>
                                            <th>{{ trans('admin.guard') }}</th>
                                            </thead>
                                            <tbody>
                                            @isset($permissions_group[$guard])
                                            @foreach($permissions_group[$guard] as $permission)
                                                <tr>
                                                    <td><input type="checkbox" name="permissions[]"
                                                               {{ in_array($permission->id, $current_permissions)?'checked':'' }} class="grid-row-checkbox checkbox-style"
                                                               value="{{ $permission->id }}"></td>
                                                    <td>{{ str_after(trans("{$permission->guard_name}.{$permission->name}"), '.') }}</td>
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
                    <div class="box-footer clearfix">
                        <button type="submit" class="pull-right btn btn-primary"
                                style="width: 120px">{{ trans('admin.save') }}</button>
                    </div>
                </div>
            </div>
            <!-- end panel -->
            <!-- end col-12 -->
        </div>
    </form>
    <!-- end row -->

    <!-- end #content -->

    <script>
        Admin.boot(function () {
            $('[name="guard"]').change(function () {
                var name = $(this).val();
                $('.data-list').html($('template.' + name).html());
                $('.data-list>table').scrollTableBody();
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
