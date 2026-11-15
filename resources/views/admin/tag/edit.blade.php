@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Tag
            <small>Edit </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('tag.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Tag</a></li>
            <li class="active">Edit Tag</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Tag</h3>
                    </div>

                    <form role="form" action="{{ route('tag.update', ['tag' => $tag->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ $tag->name }}">
                                @error('name')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label for="alias">Tag Slug</label>
                                        <input type="text" class="form-control" id="alias" name="alias" placeholder="Enter tag slug. e.g. tag-slug" value="{{ $tag->alias }}">
                                        @error('alias')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="sorting">Priority</label>
                                        <input type="text" class="form-control" id="sorting" name="sorting" placeholder="Priority" value="{{ $tag->sorting }}">
                                        @error('sorting')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta title" value="{{ $tag->meta_title }}">
                                @error('meta_title')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Meta keywords" value="{{ $tag->meta_keywords }}">
                                @error('meta_keywords')<x-error-message :message="$message"/>@enderror
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                @error('meta_description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" style="resize: vertical; min-height: 100px;">{{ $tag->meta_description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description">Tag Description</label>
                                @error('description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control ckEditor" id="description" name="description" placeholder="Tag Description Here" style="resize: vertical; min-height: 100px;">{{ $tag->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Photo</label>
                                        <input type="file" id="image" name="image" onchange="getPreview('image','photoPreview', 'none')">
                                        <p class="help-block text-yellow">Dimension: <span class="text-red">1280x720px</span>, Max <span class="text-red">300KB</span></p>
                                        @error('image')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview Image : &nbsp;</label>
                                        <img src="{{ $tag->image ? asset(Config::get('helper.uploadPath') . $tag->image) : asset('adminAssets/no_image.png') }}" class="img-responsive img-thumbnail" id="photoPreview" alt="Preview" title="Image" style="height: 100px;" />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" class="form-control" name="status">
                                            @foreach (config('helper.status') as $status)
                                                <option value="{{ $status['id'] }}" @selected($tag->status == $status['id'])>
                                                    {{ $status['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('tag.index') }}" class=" btn btn-primary pull-left">Back</a>
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

