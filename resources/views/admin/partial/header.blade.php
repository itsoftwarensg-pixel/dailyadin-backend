<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('adminDashboard') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">R</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b style="font-size: 18px;">Daily Adin</b>&nbsp;<i
                class="fa fa-spinner fa-spin fa-lg ajaxLoader" style="display:none;"></i></span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="custom-link">
            <a href="https://dailyadin.com/" target="_blank">View Website</a>
        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        @if (auth()->user()->photo)
                            <img src="{{ asset(Config::get('helper.uploadPath') . auth()->user()->photo) }}"
                                class="user-image" alt="{{ auth()->user()->name }}">
                        @else
                            <img src="{{ asset('adminAssets/no_image.png') }}" class="user-image" alt="User Image">
                        @endif
                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            @if (auth()->user()->photo)
                                <img src="{{ asset(Config::get('helper.uploadPath') . auth()->user()->photo) }}"
                                    class="img-circle" style="display: inline-block" alt="{{ auth()->user()->name }}">
                            @else
                                <img src="{{ asset('adminAssets/no_image.png') }}" class="img-circle" alt="User Image">
                            @endif
                            <p>
                                {{ auth()->user()->name }}
                                <small><b>{{ auth()->user()->role->name }}</b></small>
                                <small>Member since {{ auth()->user()->created_at->format('F j, Y') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('adminProfile') }}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
