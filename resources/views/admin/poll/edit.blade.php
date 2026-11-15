@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Poll
            <small>Edit </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('poll.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Poll</a></li>
            <li class="active">Edit Poll</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Poll</h3>
                    </div>

                    <form role="form" action="{{ route('poll.update', ['poll' => $poll->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" id="photo" name="photo" onchange="getPreview('photo','photoPreview', 'none')">
                                        <p class="help-block text-yellow">Dimension: <span class="text-red">400X240px</span>, Max <span class="text-red">300KB</span></p>
                                        @error('photo')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview Image : &nbsp;</label>
                                        <img src="{{ $poll->photo ? asset(Config::get('helper.uploadPath') . $poll->photo) : asset('adminAssets/no_image.png') }}" class="img-responsive img-thumbnail" id="photoPreview" alt="Preview" title="Image" style="height: 100px;" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="question_bn">Poll Question</label>
                                @error('question_bn')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control ckEditor" id="question_bn" name="question_bn" placeholder="Question" style="resize: vertical; min-height: 100px;">{{ $poll->question_bn }}</textarea>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" class="form-control" name="status">
                                            @foreach (config('helper.status') as $status)
                                                <option value="{{ $status['id'] }}" @selected($poll->status == $status['id'])>
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
                                        <a href="{{ route('poll.index') }}" class=" btn btn-primary pull-left">Back</a>
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

