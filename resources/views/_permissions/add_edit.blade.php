@extends('admin::_layouts.app')

@section('title', trans('admin.'.($model->id?'edit_permission':'add_permission')))

@section('breadcrumbs')
    <li><a href="{{ Admin::action('index') }}"> {{ trans_choice('admin.permission', 1) }}</a></li> @endsection

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
                        <div class="form-group row {{ $errors->has('name')?"has-error":"" }}">
                            <label class="col-control-label col-control-label-sm col-md-3 text-right asterisk">{{ trans('admin.permission') }}：</label>
                            <div class="col-md-6">
                                <input type="text" id="name"
                                       class="form-control form-control-sm @if($errors->has('name'))is-invalid @endif"
                                       value="{{ old('name', $model->name)}}">
                                    <span class="error invalid-feedback">{{$errors->first('name')}}</span>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('guard')?"has-error":"" }}">
                            <label class="col-control-label col-control-label-sm col-md-3 text-right">{{ trans('admin.guard') }}：</label>
                            <div class="col-md-6">
                                <select data-minimum-results-for-search="Infinity" name="guard"
                                        class="form-control form-control-sm select2 @if($errors->has('name'))is-invalid @endif">
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
            </div></div>
    </form>
    <!-- end row -->

    <!-- end #content -->
@endsection
