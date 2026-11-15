@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Admin
            <small>Profile - Edit</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">

                <!-- Profile Image -->
                <div class="ox box-primary">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center">Edit Profile Information</h3>
                        <form action="{{ route('adminUpdateProfile') }}" method="post">
                            @csrf
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control" name="name" placeholder="Enter Your name" value="{{ auth()->user()->name }}">
                                @error('name')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control" name="phone" placeholder="Enter phone number" value="{{ auth()->user()->phone }}">
                                @error('phone')<x-error-message :message="$message"/>@enderror
                            </div>

                            <div style="display: flex; justify-content: space-between; gap: 10px;align-items: center;">
                                <a href="{{ route('adminProfile') }}" class="btn btn-primary btn-block"><b>Cancel</b></a>
                                <button type="submit" class="btn btn-success btn-block" style="margin-top:0"><b>Update</b></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
