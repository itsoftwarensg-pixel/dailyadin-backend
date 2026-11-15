@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>User - Create New Writer</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('writer.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Writer</a></li>
            <li class="active">Add New Writer</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New Writer</h3>
                    </div>

                    <form role="form" action="{{ route('writer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                @error('name')
                                <x-error-message :message="$message"/>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="alias">Name Alias</label>
                                <input type="text" class="form-control" id="alias" name="alias" placeholder="Enter name alias. e.g. name-alias">
                                @error('alias')
                                    <x-error-message :message="$message"/>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                                @error('title')
                                    <x-error-message :message="$message"/>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                @error('description')
                                    <x-error-message :message="$message"/>
                                @enderror
                                <textarea class="form-control ckEditor" id="description" name="description" placeholder="Writer Description Here" style="resize: vertical; min-height: 100px;"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" id="photo" name="photo" onchange="getPreview('photo','photoPreview', 'none')">
                                        <p class="help-block text-yellow">Dimension: <span class="text-red">300px X 300px</span>, Max <span class="text-red">100KB</span></p>
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

                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('writer.index') }}" class=" btn btn-primary pull-left">Back</a>
                                        <span class="text-info text-center"></span>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pull-right">
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-success btn-block">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    {{-- Add CKEditor --}}
    <script src="{{ asset('adminAssets/plugins/ckeditor/ckeditor.js') }}"></script>

    {{-- Activate CKEditor --}}
    <script type="text/javascript">
        $(function() {
            $('.ckEditor').each(function(){
                CKEDITOR.replace( $(this).attr('id') );
            });
        });
    </script>

    {{--Slug generator--}}
    <script src="{{ asset('adminAssets/dist/js/slug.js') }}"></script>
    <script>
        // Bind the slug auto-update
        bindSlug('#name', '#alias');
    </script>
@endsection

