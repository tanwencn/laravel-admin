<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') &nbsp;&nbsp; {{ option('web_name') }} &#8212; TaneCN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="{{ config('admin.layout.body_class') }}">
<div class="wrapper">
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

        <!-- Main content -->
        <section class="content card-body">
            @yield('content')
        </section>
        <!-- /.content -->

        <script>
            $(function(){
                var success = '{{ session('success') }}'
                if (success != '') {
                    Admin.success(success);
                }
                @foreach($errors->all() as $error)
                Admin.errors.push('{{ $error }}');
                @endforeach

                Admin.language.csrf_token = '{{ csrf_token() }}';
                Admin.init();
            });
        </script>
    </div>

</div>
<!-- ./wrapper -->
</body>
</html>