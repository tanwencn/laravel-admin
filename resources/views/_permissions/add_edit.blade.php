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
                                    <input type="text" name="name"
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
        });
    </script>
@endsection
