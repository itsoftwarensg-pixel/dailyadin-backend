@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Admin
            <small>Profile - Change Password</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">

                <!-- Profile Image -->
                <div class="ox box-primary">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center">Change Password</h3>

                        <form action="{{ route('adminUpdatePassword') }}" method="post">
                            @csrf
                            <div class="form-group has-feedback">
                                <input type="password" class="form-control" name="current_password" placeholder="Enter current Password">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('current_password')
                                    <x-error-message :message="$message"/>
                                @enderror
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" class="form-control" name="password" placeholder="Enter Your Password">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('password')
                                    <x-error-message :message="$message"/>
                                @enderror
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                @error('password_confirmation')
                                    <x-error-message :message="$message"/>
                                @enderror
                            </div>

                            <div style="display: flex; justify-content: space-between; gap: 10px;align-items: center;">
                                <a href="{{ route('adminProfile') }}" class="btn btn-primary btn-block"><b>Cancel</b></a>
                                <button type="submit" class="btn btn-success btn-block" style="margin-top:0"><b>Submit</b></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
