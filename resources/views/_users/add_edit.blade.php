@extends('admin::_layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_user':'add_user'), 1))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ Admin::action('index') }}"> {{ trans_choice('admin.user', 1) }}</a></li>
@endsection

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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @foreach($user_name_fileds as $key => $filed)
                            <div class="form-group row {{ $errors->has($filed)?"has-error":"" }}">
                                <label class="text-right col-form-label text-right col-form-label-sm col-md-3 {{ $key==0?"asterisk":"" }}">{{ trans('admin.'.$filed) }}
                                    ：</label>
                                <div class="col-md-6">
                                    <input type="text" name="{{ $filed }}" class="form-control form-control-sm @if($errors->has($filed)) is-invalid @endif"
                                           value="{{ old($filed, $model->{$filed})}}">
                                        <span class="error invalid-feedback">{{$errors->first($filed)}}</span>
                                </div>
                            </div>
                        @endforeach

                        <div class="form-group row {{ $errors->has('metas.avatar')?"has-error":"" }}">
                            <label class="text-right col-form-label text-right col-form-label-sm col-md-3">{{ trans('admin.avatar') }}
                                ：</label>
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <input id="avatar" readonly style="width:300px; float: left" name="metas[avatar]"
                                           type="text"
                                           class="form-control form-control-sm @if($errors->has('metas.avatar')) is-invalid @endif"
                                           value="{{ old('metas.avatar', $model->getMetas('avatar')) }}">
                                        <span class="error invalid-feedback">{{$errors->first('avatar')}}</span>
                                    <div class="input-group-append">
                                        <button type="button" style="float: left;"
                                                class="btn btn-default select-image btn-"><i
                                                    class="glyphicon glyphicon-folder-open"></i> {{ trans('admin.select_image') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('edit_role')
                            <div class="form-group row {{ $errors->has('role')?"has-error":"" }}">
                                <label class="text-right col-form-label text-right col-form-label-sm col-md-3 asterisk">{{ trans_choice('admin.role', 1) }}
                                    ：</label>
                                <div class="col-md-6">
                                    <select name="role[]" class="select2 form-control form-control-sm @if($errors->has('role')) is-invalid @endif"
                                            multiple="multiple">
                                        @foreach($roles as $name => $title)
                                            <option {{ in_array($name, old('role', $model->roles->pluck('name')->all()))?'selected':'' }} value="{{ $name }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
                                        <span class="error invalid-feedback">{{$errors->first('role')}}</span>
                                </div>
                            </div>
                        @endcan
                        @if($model->id)
                            <div class="form-group row">
                                <label for="password"
                                       class="col-sm-3 text-right col-form-label text-right col-form-label-sm"></label>

                                <div class="col-sm-6">
                                    <div class="callout callout-warning"
                                         style="margin:20px 0 0 0">{{ trans('admin.chang_password_tip') }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row {{ $errors->has('password')?"has-error":"" }}">
                            <label for="password"
                                   class="col-sm-3 text-right col-form-label text-right col-form-label-sm">密码</label>
                            <div class="col-sm-6">
                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control form-control-sm @if($errors->has('password')) is-invalid @endif">
                                    <span class="error invalid-feedback">{{$errors->first('password')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row  {{ $errors->has('password')?"has-error":"" }}">
                            <label for="password_confirmation"
                                   class="col-sm-3 text-right col-form-label text-right col-form-label-sm">确认密码</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                    </div>
                                    <input type="password" name="password_confirmation"
                                           class="form-control form-control-sm">
                                </div>
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
            <!-- end col-12 -->
        </div>
    </form>
    <!-- end row -->

    <!-- end #content -->
    <script>
        Admin.boot(function () {
            Finder.disk().click('.select-image', '#avatar');
        });
    </script>
@endsection