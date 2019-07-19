<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') &nbsp;&nbsp; {{ option('web_name') }} &#8212; TaneCN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
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
                <li class="active">@yield('title')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content box-body">
            @yield('content')
        </section>
        <!-- /.content -->

        <script>
            $(function () {
                Blog.init();
                currentMenu('{{ url()->current() }}');

                toastr.clear();
                @if (session('toastr_success'))
                toastr.success('{{ session('toastr_success') }}')
                @endisset

                @foreach($errors->all() as $error)
                toastr.error('{{ $error }}', '{{ trans('admin.failed') }}')
                @endforeach
            });
        </script>
    </div>

</div>
<!-- ./wrapper -->
</body>
</html>