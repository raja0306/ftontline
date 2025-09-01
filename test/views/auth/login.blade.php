<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Login - Centrixplus</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Centrixplus - Callcenter CRM System" name="description" />
<meta content="Centrixplus" name="author" />
<link rel="shortcut icon" href="{{asset('/assets')}}/images/favicon.ico">
<link href="{{asset('/assets')}}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="account-pages my-5 pt-sm-5">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center mb-5 text-muted">
                <a href="index.html" class="d-block auth-logo">
                    <img src="{{asset('/assets')}}/img/logo.png" alt="" height="50" class="auth-logo-dark mx-auto">
                    <img src="{{asset('/assets')}}/img/logo.png" alt="" height="50" class="auth-logo-light mx-auto">
                </a>
                <p class="mt-3">Sign in to continue to Centrix.</p>
            </div>
        </div>
    </div>
    <!-- end row -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card">

                <div class="card-body">

                    <div class="p-2">
                        <div class="text-center">

                            <div class="avatar-md mx-auto">
                                <div class="avatar-title rounded-circle bg-light">
                                    <i class="bx bx-user-pin h1 mb-0 text-success"></i>
                                </div>
                            </div>
                            <div class="p-2 mt-4">
                                <form method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter Username">
                                         @if ($errors->has('email'))
                                            <span class="text-danger">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                         @if ($errors->has('password'))
                                            <span class="text-danger">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-check" name="remember">
                                        <label class="form-check-label" for="remember-check">
                                            Remember me
                                        </label>
                                    </div>
                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-success waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="{{asset('/assets')}}/libs/jquery/jquery.min.js"></script>
<script src="{{asset('/assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('/assets')}}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{asset('/assets')}}/libs/simplebar/simplebar.min.js"></script>
<script src="{{asset('/assets')}}/libs/node-waves/waves.min.js"></script>
<script src="{{asset('/assets')}}/js/app.js"></script>

</body>
</html>
