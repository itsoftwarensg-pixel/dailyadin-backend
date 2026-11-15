@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Admin
            <small>Profile</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <!-- Profile Image -->
                <div class="">
                    <div class="box-body box-profile">
                        @if(auth()->user()->photo)
                            <img src="{{ asset(Config::get('helper.uploadPath') . auth()->user()->photo) }}" class="profile-user-img img-responsive" alt="{{ auth()->user()->name }}">
                        @else
                            <img src="{{asset('adminAssets/no_image.png')}}" class="profile-user-img img-responsive" alt="User Image">
                        @endif

                        <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>

                        <p class="text-muted text-center">{{ auth()->user()->email }}</p>

                        <ul class="list-group list-group-unborderedd">
                            <li class="list-group-item">
                                <b>Name</b> <span class="pull-right">{{ auth()->user()->name }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Role</b> <span class="pull-right">{{ auth()->user()->role->name }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Username(Email)</b> <span class="pull-right">{{ auth()->user()->email }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Phone</b> <span class="pull-right">{{ auth()->user()->phone }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Member since</b> <span class="pull-right">{{ auth()->user()->created_at->format('F j, Y') }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Last updated</b>
                                <span class="pull-right">
                                    {{ auth()->user()->updated_at ? auth()->user()->updated_at->format('F j, Y h:i:s A') : '' }}
                                </span>
                            </li>
                        </ul>

                        <div style="display: flex; justify-content: space-between; gap: 10px;align-items: center;">
                            <a href="{{ route('adminChangePhoto') }}" class="btn bg-blue btn-block"><b>Change Photo</b></a>
                            <a href="{{ route('adminEditProfile') }}" class="btn bg-purple btn-block" style="margin-top:0"><b>Edit Profile</b></a>
                            <a href="{{ route('adminChangePassword') }}" class="btn bg-maroon btn-block" style="margin-top:0"><b>Change Password</b></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
