@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Photo
            <small>Add New Category</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('photo-category.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Photo</a></li>
            <li class="active">Add New Category</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New Photo Category</h3>
                    </div>

                    <form role="form" action="{{ route('photo-category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                        @error('name')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter category slug. e.g. category-slug">
                                        @error('slug')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                @error('description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control ckEditor" id="description" name="description" placeholder="Description" style="resize: vertical; min-height: 100px;"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('photo-category.index') }}" class=" btn btn-primary pull-left">Back</a>
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
@endsection

