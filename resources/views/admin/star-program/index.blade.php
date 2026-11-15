@extends('layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            Star Program
            <small>Manage Star Program Section</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">Manage Star Program</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Star Program Details</h3>
                    </div>
                    <form role="form" action="{{ route('star-program.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="identity">Identity / Name</label>
                                <input type="text" class="form-control" id="identity" name="identity"
                                    placeholder="e.g. Hamza Choudhury"
                                    value="{{ old('identity', $starProgram->identity) }}">
                                @error('identity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="details">Details</label>
                                <textarea class="form-control" id="details" name="details" placeholder="Short description..."
                                    style="min-height: 100px;">{{ old('details', $starProgram->details) }}</textarea>
                                @error('details')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="quote">Quote</label>
                                <textarea class="form-control" id="quote" name="quote" placeholder="Star's quote..." style="min-height: 80px;">{{ old('quote', $starProgram->quote) }}</textarea>
                                @error('quote')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="video_link">Video Link (YouTube/Facebook)</label>
                                <input type="text" class="form-control" id="video_link" name="video_link"
                                    placeholder="https://..." value="{{ old('video_link', $starProgram->video_link) }}">
                                @error('video_link')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="photo">Photo</label>
                                @if ($starProgram->photo)
                                    <br>
                                    <img src="{{ asset(config('helper.uploadPath') . $starProgram->photo) }}"
                                        alt="Current Photo" style="max-height: 100px; margin-bottom: 10px;">
                                @endif
                                <input type="file" class="form-control" id="photo" name="photo">
                                <p class="help-block">Upload new photo to replace.</p>
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" @selected(old('status', $starProgram->status) == 1)>Active</option>
                                    <option value="2" @selected(old('status', $starProgram->status) == 2)>Inactive</option>
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
