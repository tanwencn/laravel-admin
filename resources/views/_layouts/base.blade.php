<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') &nbsp;&nbsp;&nbsp;{{ option('web_name', config('app.name')) }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="shortcut icon" href="{{ asset(mix('/logo.png', 'vendor/laravel-admin')) }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset(mix('/css/vendor.css', 'vendor/laravel-admin')) }}">
    <link rel="stylesheet" href="{{ asset(mix('/css/app.css', 'vendor/laravel-admin')) }}">
    <script src="{{ asset(mix('/js/vendor.js', 'vendor/laravel-admin')) }}"></script>
    <script src="{{ asset(mix('/js/app.js', 'vendor/laravel-admin')) }}"></script>

    {!! Admin::asset()->header() !!}
    @includeIf('admin::header')
</head>
<body class="{{ config('admin.layout.body_class') }}">
<div class="wrapper">

    <!-- Navbar -->
@include('admin::top_navbar')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link" style="font-size: 1.25rem">
            {!! config('admin.layout.logo') !!}
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ Auth::user()->avatar }}" class="img-circle elevation-2">
                </div>
                <div class="info">
                    <a href="{{ route('admin.users.edit', Auth::id()) }}" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2" style="padding-bottom: 5rem">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="true">
                {!! Admin::menu()->render() !!}
                <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@yield('title')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route(config('admin.router.index')) }}"><i
                                            class="fa fa-dashboard"></i> {{ trans('admin.dashboard') }}</a></li>
                            @yield('breadcrumbs')
                            <li class="breadcrumb-item active">
                                @hasSection('active_title')
                                    @yield('active_title')
                                @else
                                    @yield('title')
                                @endif</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @if(config('admin.layout.footer') !== false)
        <footer class="main-footer">
            {!! config('admin.layout.footer') !!}
        </footer>
@endif

<!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

{!! Admin::asset()->footer() !!}
@includeIf('admin::footer')
<script>
    var success = '{{ session('success') }}'
    if (success != '') {
        toastr.success(success);
    }
    @foreach($errors->all() as $error)
    Admin.errors.push('{{ $error }}');
    @endforeach
        Admin.language = {
        confirmTitle: '{{ trans('admin.please_confirm') }}',
        confirm: '{{ trans('admin.confirm') }}',
        cancel: "{{ trans('admin.cancel') }}",
        trashMessage: "{{ trans('admin.trash_message') }}",
        deleteMessage: "{{ trans('admin.delete_message') }}",
        pleaseSelectData: "{{ trans('admin.please_select') }}{{ trans('admin.data') }}",
        ok: "{{ trans('admin.ok') }}",
        csrf_token: '{{ csrf_token() }}'
    };

    Finder.default = {title: "{{ trans('admin.select_file') }}", url: "{{ route('admin.elfinder.show') }}"};

    Admin.init();
</script>
</body>
</html>