<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daily Adin | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- Required CSS --}}
    <link rel="stylesheet" href="{{ asset('adminAssets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/font-awesome-4.5.0/css/font-awesome.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('adminAssets/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/iCheck/square/blue.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><strong>Daily Adin</strong></a>

        </div>

        <div class="login-box-body">

            <h3 style="margin-top: 0;text-align: center">Sign in</h3>
            @if (session()->has('error'))
                <div class="mx-5 alert alert-danger text-center">
                    {{ session()->get('error') }}
                </div>
            @endif

            @if (session()->has('message'))
                <div class="mx-5 alert alert-success text-center">
                    {{ session()->get('message') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="email" placeholder="Enter Your Email Address">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                        <x-error-message :message="$message" />
                    @enderror
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Enter Your Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @error('password')
                        <x-error-message :message="$message" />
                    @enderror
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="btnSubmit">Sign
                            In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- REQUIRED JS -->
    <script src="{{ asset('adminAssets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('adminAssets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminAssets/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

</html>
