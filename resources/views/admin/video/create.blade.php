@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Video
            <small>Add New </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('video.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Video</a></li>
            <li class="active">Add New Video</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New Video</h3>
                    </div>

                    <form role="form" action="{{ route('video.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="video_category_id">Video Category</label>
                                <select id="video_category_id" class="form-control" name="video_category_id">
                                    <option value="">Select category</option>
                                    @foreach (config('helper.video_categories') as $id => $name)
                                        <option value="{{ $id }}" @selected(old('video_category_id') == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('video_category_id')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter video title">
                                @error('title')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group">
                                <label for="youtube_url">YouTube Link <small class="text-muted">(optional)</small></label>
                                <input type="url" class="form-control" id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                                @error('youtube_url')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group">
                                <label for="embed_code">YouTube Embed Code or Video ID <small class="text-muted">(optional)</small></label>
                                <input type="text" class="form-control" id="embed_code" name="embed_code" value="{{ old('embed_code') }}" placeholder="Paste iframe embed code or video ID">
                                <p class="help-block">Provide either a YouTube link or an embed code/video ID.</p>
                                @error('embed_code')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                @error('description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control ckEditor" id="description" name="description" placeholder="Question" style="resize: vertical; min-height: 100px;"></textarea>
                            </div>
                            {{--<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Choose photo</label>
                                        <input type="file" id="photo" name="photo" onchange="getPreview('photo','photoPreview', 'none')">
                                        <p class="help-block text-yellow">Dimension: <span class="text-red">400X240px</span>, Max <span class="text-red">300KB</span></p>
                                        @error('photo')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview Image : &nbsp;</label>
                                        <img src="{{asset('adminAssets/no_image.png')}}" class="img-responsive img-thumbnail" id="photoPreview" alt="Preview" title="Default Image" style="height: 100px;" />
                                    </div>
                                </div>
                            </div>--}}

                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('video.index') }}" class=" btn btn-primary pull-left">Back</a>
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

