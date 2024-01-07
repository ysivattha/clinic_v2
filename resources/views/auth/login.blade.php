<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User Login | Vdoo ERP</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .login-page{
            height: auto;
            background: url("{{asset('img/bg.jpg')}}") center center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box mt-5">
        <div class="login-logo">
            <a href="#">
                <img src="{{asset('img/logo.png')}}" alt="" width="72"> 
                <strong class="text-light">Vdoo ERP</strong>
            </a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-success font-weight-bold" style="border-bottom: 1px solid #ddd">
                    LOGIN TO CONTINUE
                </p>
                @if(count($errors)>0)
                    <div class="alert alert-warning" role="alert">
                        Invalid username or password!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <form method="POST" action="{{ route('login.custom') }}">
                    @csrf
                    <label for="username" class="col-form-label">Username</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id='username' name='username' required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-user text-primary"></span>
                            </div>
                        </div>
                       
                    </div>
                    <label for="password" class="col-form-label">Password</label>
                    <div class="input-group mb-3">
                      <input type="password" class="form-control" id='password' name="password" required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-lock text-primary"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-8">
                        <div class="icheck-primary">
                          <input type="checkbox" id="remember">
                          <label for="remember">
                            Remember Me
                          </label>
                        </div>
                      </div>
                      <!-- /.col -->
                      <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                      </div>
                      <!-- /.col -->
                    </div>
                  </form>
            
            </div>
        </div>
    
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/adminlte.min.js')}}"></script>
</body>
</html>
