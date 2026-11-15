<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Daily Adin | Dashboard')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Required CSS --}}
    <link rel="stylesheet" href="{{ asset('adminAssets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/font-awesome-4.5.0/css/font-awesome.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('adminAssets/bootstrap/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/ionicons/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/dist/css/custom.css') }}">
    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body class="skin-blue sidebar-mini fixed">
    <div class="wrapper">
        {{-- Header --}}
        @include('admin.partial.header')

        {{-- Sidebar --}}
        @include('admin.partial.sidebar')

        {{-- Main Content --}}
        <div class="content-wrapper">
            @if (session()->has('error') || session()->has('success') || session()->has('message') || session()->has('status'))
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4" style="position: fixed;top: 70px;right: 5px;z-index: 1000;">
                        @if (session()->has('error'))
                            <div class="callout callout-danger alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                                    style="color:#fff;opacity: 1">×</button>
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        @if (session()->has('success'))
                            <div class="callout callout-success alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                                    style="color:#fff;opacity: 1">×</button>
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        @if (session()->has('message'))
                            <div class="callout callout-success alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                                    style="color:#fff;opacity: 1">×</button>
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        @if (session()->has('status'))
                            <div class="callout callout-success alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"
                                    style="color:#fff;opacity: 1">×</button>
                                {{ session()->get('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        @include('admin.partial.footer')
    </div>

    <!-- REQUIRED JS -->
    <script src="{{ asset('adminAssets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('adminAssets/plugins/jQuery/select2.min.js') }}"></script>
    <script src="{{ asset('adminAssets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminAssets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('adminAssets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('adminAssets/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('adminAssets/dist/js/functions.js') }}"></script>
    @yield('js')
</body>

</html>
