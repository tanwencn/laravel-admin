@extends('admin::layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_user':'add_user'), 1))

@section('breadcrumbs') <li><a href="{{ Admin::action('index') }}"> {{ trans_choice('admin.user', 1) }}</a></li> @endsection

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
                        <div class="form-group {{ $errors->has('email')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans('admin.email') }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('email'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('email')}}
                                    </label>
                                @endif
                                <input type="text" name="email" class="form-control"
                                       value="{{ old('email', $model->email)}}">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('name')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans('admin.name') }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('name'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('name')}}
                                    </label>
                                @endif
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $model->name)}}">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('metas.avatar')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans('admin.avatar') }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('metas.avatar'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('metas.avatar')}}
                                    </label>
                                @endif
                                <input id="avatar" readonly style="width:300px; float: left" name="metas[avatar]" type="text"
                                       class="form-control"
                                       value="{{ old('metas.avatar', $model->getMetas('avatar')) }}">
                                <button type="button" style="width: 100px; float: left;"
                                        class="btn btn-default select-image"><i class="glyphicon glyphicon-folder-open"></i> {{ trans('admin.select_image') }}</button>
                            </div>
                        </div>
                        @role('superadmin')
                        <div class="form-group {{ $errors->has('role')?"has-error":"" }}">
                            <label class="control-label col-md-2">{{ trans_choice('admin.role', 1) }}：</label>
                            <div class="col-md-8">
                                @if($errors->has('role'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('role')}}
                                    </label>
                                @endif
                                <select name="role[]" class="select2 form-control" multiple="multiple">
                                    @foreach($roles as $name => $title)
                                        <option {{ in_array($name, old('role', $model->roles->pluck('name')->all()))?'selected':'' }} value="{{ $name }}">{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endrole
                        @if($model->id)
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label"></label>

                            <div class="col-sm-8">
                                    <div class="callout callout-warning" style="margin:20px 0 0 0">{{ trans('admin.chang_password_tip') }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group {{ $errors->has('password')?"has-error":"" }}">
                            <label for="password" class="col-sm-2 control-label">密码</label>

                            <div class="col-sm-8">
                                @if($errors->has('password'))
                                    <label class="control-label">
                                        <i class="fa fa-times-circle-o"></i>{{$errors->first('password')}}
                                    </label>
                                @endif
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('password')?"has-error":"" }}">
                            <label for="password_confirmation" class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
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
        Finder.disk().click('.select-image', '#avatar');
    });
</script>
@endsection

