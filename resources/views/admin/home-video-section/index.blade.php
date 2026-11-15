@extends('layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            Home Video Section
            <small>Manage Video</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">Manage Home Video</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Video Details</h3>
                    </div>
                    <form role="form" action="{{ route('home-video-section.store') }}" method="POST">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="heading">Video heading</label>
                                <input type="text" class="form-control" id="heading" name="heading"
                                    placeholder="Live Video heading" value="{{ old('heading', $video->heading) }}">
                                @error('heading')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="embed_code">Video Link / Embeded Code</label>
                                <p class="help-block small">Example: (
                                    https://www.facebook.com/dailyadin/videos/2931137967198578 )</p>
                                <textarea class="form-control" id="embed_code" name="embed_code" placeholder="Embeded Code" style="min-height: 100px;">{{ old('embed_code', $video->embed_code) }}</textarea>
                                @error('embed_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="type">Video Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="1" @selected(old('type', $video->type) == 1)>YouTube</option>
                                            <option value="2" @selected(old('type', $video->type) == 2)>Facebook</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="is_live">Is Live?</label>
                                        <select name="is_live" id="is_live" class="form-control">
                                            <option value="1" @selected(old('is_live', $video->is_live) == 1)>Yes (Live)</option>
                                            <option value="2" @selected(old('is_live', $video->is_live) == 2)>No</option>
                                        </select>
                                        @error('is_live')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Display Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" @selected(old('status', $video->status) == 1)>Active</option>
                                    <option value="2" @selected(old('status', $video->status) == 2)>Inactive</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="text-right">
                                <button type="submit" class="btn btn-warning"
                                    style="padding-left: 50px; padding-right: 50px;">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
