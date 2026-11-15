@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Admin
            <small>Profile - Change Photo</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="text-center">
                    @if(auth()->user()->photo)
                        <img src="{{ asset(Config::get('helper.uploadPath') . auth()->user()->photo) }}" class="profile-user-img img-responsive" alt="{{ auth()->user()->name }}">
                    @else
                        <img src="{{asset('adminAssets/no_image.png')}}" class="profile-user-img img-responsive" alt="User Image">
                    @endif
                    <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>
                    <p class="text-muted text-center">{{ auth()->user()->email }}</p>
                </div>

                @error('photo')
                    <div class="callout callout-danger alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color:#fff;opacity: 1">×</button>
                        {{ $message }}
                    </div>
                @enderror

                <div class="box">
                    <form role="form" action="{{ route('adminUpdatePhoto') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="userImg">File input</label>
                                        <input type="file" id="photo" name="photo" onchange="getPreview('photo','photoPreview', 'none')">
                                        <p class="help-block text-red">Image Size must be less than 50KB.<br>Dimension 215x215px</p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview Image : &nbsp;</label>
                                        <img src="{{asset('adminAssets/no_image.png')}}" class="img-responsive img-thumbnail" id="photoPreview" name="photoPreview" style="height: 100px;">
                                        <input type="hidden" name="hidden_img" value="">
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <div style="display: flex; justify-content: space-between; gap: 10px;align-items: center;">
                                <a href="{{ route('adminProfile') }}" class="btn btn-primary btn-block">Back</a>
                                <button type="submit" class="btn btn-success btn-block" style="margin-top:0">Update</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </section>
@endsection
