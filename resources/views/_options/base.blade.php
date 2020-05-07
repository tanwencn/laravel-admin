@extends('admin::_layouts.app')

@section('title', trans('admin.setting'))

@section('content')
    <style>
        .form-horizontal input[type="text"], select {
            max-width: 500px !important;
        }
    </style>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3" style="padding-left:0;">
                <div class="card solid">
                    <div class="card-body p-0 sidebar-light-primary" style="">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"
                            data-accordion="true">
                            @include('admin::_options.nav')
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-9" style="padding-right:0;">
                <form method="POST" action="{{ Admin::action('save') }}" class="form-horizontal">
                    <div class="card">
                        <div class="card-body">
                            {{ csrf_field() }}
                            @yield('form')
                        </div>
                        <div class="card-footer clearfix">
                            <button type="button" style="width: 120px"
                                    class="btn btn-primary float-right btn-save">{{ trans('admin.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection