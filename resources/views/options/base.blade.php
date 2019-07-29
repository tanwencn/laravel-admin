@extends('admin::layouts.app')

@section('title', trans('admin.setting'))

@section('content')
    <style>
        .form-horizontal input[type="text"], select {
            max-width: 500px !important;
        }
    </style>

    <div class="col-md-3" style="padding-left:0;">
        <div class="box box-solid">
            <div class="box-body no-padding" style="">
                <ul class="nav nav-pills nav-stacked">
                    @include('admin::options.nav')
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-9" style="padding-right:0;">
        <form method="POST" action="{{ Admin::action('save') }}" class="form-horizontal">
            <div class="box box-default">
                <div class="box-header"></div>
                <div class="box-body">
                    {{ csrf_field() }}
                    @yield('form')
                </div>
                    <div class="box-footer clearfix">
                        <button type="button" style="width: 120px"
                                class="btn btn-primary pull-right btn-save">{{ trans('admin.save') }}</button>
                    </div>
            </div>
        </form>
    </div>
@endsection