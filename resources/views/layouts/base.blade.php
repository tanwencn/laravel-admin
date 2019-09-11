<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') &nbsp;&nbsp;&nbsp;{{ option('web_name') }} &#8212; TaneCN</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="shortcut icon" href="{{ asset('vendor/laravel-admin/logo.png') }}" type="image/x-icon">

{!! Admin::asset()->head() !!}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>T</b>c</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Tane</b>CN</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="javascript:;" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ Auth::user()->avatar }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ Auth::user()->username }}
                                    <small>{{ Auth::user()->name }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('admin.users.edit', Auth::id()) }}"
                                       class="btn btn-default btn-flat">{{ trans('admin.edit_profile') }}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('admin.logout') }}"
                                       class="btn btn-default btn-flat">{{ trans('admin.logout') }}</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ option('web_url') }}"><i class="fa fa-home"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- /.search form -->
            {{--<div class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </div>--}}
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                {!! Admin::menu()->render() !!}
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('description')</small>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-dashboard"></i> {{ trans('admin.dashboard') }}</a></li>
                @yield('breadcrumbs')
                <li class="active">@hasSection('active_title')
                        @yield('active_title')
                    @else
                        @yield('title')
                    @endif</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content box-body">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0
        </div>
        <strong>Made by The <a href="http://www.tanecn.com" target="_blank">TaneCN</a>.</strong>
    </footer>
</div>
<!-- ./wrapper -->

{!! Admin::asset()->footer() !!}

<script>
    console.log($('body').attr('class'));
    console.log($('body').prop('class'));
    console.log($('body').class);
    @foreach($errors->all() as $error)
    Admin.errors.push('{{ $error }}');
    @endforeach
        Admin.info = {
        timeout_load: '{{ trans('admin.timeout_load') }}',
        failed: '{{ trans('admin.failed') }}',
        deleteTitle: '{{ trans('admin.delete_confirm') }}',
        deleteText: "{{ trans('admin.delete') }}",
        cancelText: "{{ trans('admin.cancel') }}",
        trashMessage: "{{ trans('admin.trash_message') }}",
        deleteMessage: "{{ trans('admin.delete_message') }}",
        pleaseSelectData: "{{ trans('admin.please_select_data') }}",
        ok: "{{ trans('admin.ok') }}",
        toastr_success: '{{ session('toastr_success') }}',
        csrf_token: '{{ csrf_token() }}'
    };

    Finder.default = {title: "{{ trans('admin.select_file') }}", url: "{{ route('admin.elfinder.show') }}"};
    Admin.init();
</script>
</body>
</html>