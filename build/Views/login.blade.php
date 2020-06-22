<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ trans('admin.login') }} &nbsp;&nbsp; {{ option('web_name', config('app.name')) }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="{{ asset(mix('css/vendor.css', 'vendor/laravel-admin')) }}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ route('admin.login') }}"><b>{{ option('web_name', config('app.name')) }}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
    <p class="login-box-msg">{{ trans('admin.login') }}</p>

    <form action="{{ route('admin.login') }}" method="post">
      {{ csrf_field() }}
      <div class="input-group mb-3">
        <input type="text" class="form-control @if($errors->has($username)) is-invalid @endif" placeholder="{{ trans('admin.'.$username) }}" value="{{ old($username) }}" name="{{ $username }}">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-envelope"></span>
          </div>
        </div>
        <span class="error invalid-feedback">{{$errors->first($username)}}</span>
      </div>

      <div class="input-group mb-3">
        <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" placeholder="{{ trans('admin.password') }}" name="password">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-lock"></span>
          </div>
        </div>
        <span class="error invalid-feedback">{{$errors->first('password')}}</span>
      </div>

      <div class="row">
        <div class="col-8">
          <div class="icheck-primary">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">
              {{ trans('admin.remember_me') }}
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block">{{ trans('admin.login') }}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    </div>
  </div>
  <!-- /.login-card-body -->
</div>
<!-- /.login-box -->


<script src="{{ asset(mix('js/vendor.js', 'vendor/laravel-admin')) }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
