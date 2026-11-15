@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>User - Create New User</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.index') }}"><i class="fa fa-dashboard fa-fw"></i>All  User</a></li>
            <li class="active">Add New User</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New User</h3>
                    </div>

                    <!-- form start -->
                    <form role="form" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" >
                                        @error('email')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select class="form-control" name="role_id" >
                                            <option value="">Select Type</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                        @error('name')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                                        @error('phone')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password">Password <small class="text-muted">default 123456</small></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="123456" onfocus="this.value = '';">
                                        @error('password')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" >
                                        @error('password_confirmation')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">File input</label>
                                        <input type="file" id="photo" name="photo" onchange="getPreview('photo','photoPreview', 'none')">
                                        <p class="help-block text-red">Image Size must be less than 50KB.<br>Dimension 215x215px</p>
                                        @error('photo')
                                            <x-error-message :message="$message"/>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview Image : &nbsp;</label>
                                        <img src="{{asset('adminAssets/default.jpg')}}" class="img-responsive img-thumbnail" id="photoPreview" alt="Preview" title="Default Image" style="width: 100px; height: 100px;" />
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer text-center">
                            <a href="{{ route('user.index') }}" class="btn btn-primary pull-left">Back</a>
                            <!--  -->
                            <button type="submit" class="btn btn-success pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
