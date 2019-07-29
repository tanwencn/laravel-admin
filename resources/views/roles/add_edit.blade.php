@extends('admin::layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_role':'add_role'), 1))

@section('breadcrumbs') <li><a href="{{ Admin::action('index') }}"> {{ trans('admin.role') }}</a></li> @endsection

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
                        <div class="form-group {{ $errors->has('permissions')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans_choice('admin.permissions', 1) }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('permissions'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('permissions')}}
                                    </label>
                                @endif
                                <select multiple="multiple" size="20" name="permissions[]" class="form-control">
                                    @foreach($permissions as $permission)
                                        <option {{ in_array($permission->id, $current_permissions)?'selected':'' }} value="{{ $permission->id }}">{{ str_after(trans("{$permission->guard_name}.{$permission->name}"), '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
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
    Admin.boot(function(){
        $('select[name="permissions[]"]').bootstrapDualListbox();
    });
</script>
@endsection
