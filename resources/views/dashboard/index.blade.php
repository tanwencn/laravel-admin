@extends('admin::layouts.app')

@section('title', trans('admin.dashboard'))

@section('description', trans('admin.control_panel'))

@section('content')
    <!-- /.row -->
    <!-- Main row -->
    {{ Widget::group('admin_dashboard')->display() }}
    <!-- /.row (main row) -->
@endsection