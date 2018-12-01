<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Ali & Abbas Travels</title>
  <link rel="icon" href="{{asset('./img/globe.png')}}">
  <link rel="stylesheet" href="{{asset('/css/app.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('/css/blue.css')}}">
  <link rel="stylesheet" href="{{asset('/css/blue.png')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('/css/adminlte.min.css')}}">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    html {
      height: 100%;
    }
    
    .hold-transition,
    .login-page {
      margin: 0;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background: linear-gradient(#bdc3c7, #2c3e50);
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box" id="app">
    <div class="login-logo">
      <a href="#"><img src="{{asset('./img/logo.png')}}" alt="logo"></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="input-group mb-3">
            <input id="email" type="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>

            @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span> @endif
          </div>

          <div class="input-group mb-3">
            <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>

            @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span> @endif
          </div>

          <div class="row">
            <div class="col-8">
              <div class="checkbox icheck">
                <label>
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                        </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Login') }}</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <div class="social-auth-links text-center mb-3">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-primary">
            <i class="fab fa-facebook-f mr-2"></i> Sign in using Facebook
          </a>
          <a href="#" class="btn btn-block btn-danger">
            <i class="fab fa-google-plus-g mr-2"></i> Sign in using Google+
          </a>
        </div>
        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- REQUIRED SCRIPTS -->

  @auth
  <script>
    window.user = @json(auth() -> user())
  </script>
  @endauth

  <script src="{{asset('/js/app.js')}}"></script>
  <!-- jQuery -->
  <script src="{{asset('/js/jquery.min.js')}}"></script>
  <!-- iCheck -->
  <script src="{{asset('/js/icheck.min.js')}}"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      })
    })
  </script>
</body>

</html>