@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Photo Album
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('photo-album.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Photo Album</a></li>
            <li class="active">Edit Photo Album</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <form role="form" action="{{ route('photo-album.update', $photoAlbum->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Photo Album</h3>
                        </div>
                        <div class="box-body">
                            {{-- Main Photo Album Section --}}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        {{-- Category --}}
                                        <div class="form-group">
                                            <label for="photo_category_id">Photo Category</label>
                                            <select
                                                name="photo_category_id"
                                                id="photo_category_id"
                                                class="form-control @error('photo_category_id') is-invalid @enderror"
                                            >
                                                <option value="">Select Category</option>
                                                @foreach($photoCategories as $category)
                                                    <option value="{{ $category->id }}"
                                                        @selected(old('photo_category_id', $photoAlbum->photo_category_id) == $category->id)>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('photo_category_id')<x-error-message :message="$message"/>@enderror
                                        </div>

                                        {{-- Title --}}
                                        <div class="form-group">
                                            <label for="name">Album Name</label>
                                            <input
                                                type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name"
                                                name="name"
                                                value="{{ old('name', $photoAlbum->name) }}"
                                            />
                                            @error('name')<x-error-message :message="$message"/>@enderror
                                        </div>

                                        {{-- Photographer --}}
                                        <div class="form-group">
                                            <label for="photographer_name">Photographer Name</label>
                                            <input
                                                type="text"
                                                class="form-control @error('photographer_name') is-invalid @enderror"
                                                id="photographer_name"
                                                name="photographer_name"
                                                value="{{ old('photographer_name', $photoAlbum->photographer_name) }}"
                                            />
                                            @error('photographer_name')<x-error-message :message="$message"/>@enderror
                                        </div>

                                        {{-- Main - Album Image --}}
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label for="image">Album Main Image</label>
                                                    <input
                                                        type="file"
                                                        id="image"
                                                        name="image"
                                                        class="@error('image') is-invalid @enderror"
                                                        onchange="previewImage(this,'bgImg')"
                                                    />
                                                    @error('image')<x-error-message :message="$message"/>@enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group text-right">
                                                    <img
                                                        src="{{ $photoAlbum->image ? asset(Config::get('helper.uploadPath').$photoAlbum->image) : asset('adminAssets/no_image.png') }}"
                                                        id="bgImg"
                                                        alt="Default Image"
                                                        class="img-responsive img-thumbnail"
                                                        style="width: 100px"
                                                    />
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Status --}}
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select id="status" class="form-control" name="status">
                                                @foreach (config('helper.status') as $status)
                                                    <option value="{{ $status['id'] }}" @selected($photoAlbum->status == $status['id'])>
                                                        {{ $status['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')<x-error-message :message="$message"/>@enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        {{-- Description --}}
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control ckEditor" id="description" name="description" style="resize: vertical; min-height: 100px;">{{ old('description', $photoAlbum->description) }}</textarea>
                                            @error('description')<x-error-message :message="$message"/>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Show all errors --}}
                            @if ($errors->any())
                                <div class="box-body">
                                    <div class="alert text-red">
                                        <h5><strong>There were some problems with your input:</strong></h5>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box no-border">
                        <div class="box-body" style="padding: 20px;">
                            {{-- Existing Contents --}}
                            <div id="photo_container">
                                @php
                                    // Normalize existing contents to arrays
                                    $oldContents = $photoAlbum->contents;
                                @endphp

                                @foreach($oldContents as $index => $content)
                                    <div class="well mb-2" id="content-{{ $index }}">
                                        {{-- Hidden input for $content['id']  ---> ($photo->id) --}}
                                        <input type="hidden" name="contents[{{ $index }}][id]" value="{{ $content['id'] }}">
                                        {{-- Keep old image if no new one is uploaded --}}
                                        <input type="hidden" name="contents[{{ $index }}][content_hidden_image]" value="{{ $content['image'] }}">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                {{-- Caption --}}
                                                <div class="form-group">
                                                    <input type="text" name="contents[{{ $index }}][caption]" class="form-control"
                                                           placeholder="Caption" value="{{ $content['caption'] ?? '' }}">
                                                    @error('contents.' . $index . '.caption')<x-error-message :message="$message"/>@enderror
                                                </div>

                                                {{-- Photographer --}}
                                                <div class="form-group">
                                                    <input type="text" name="contents[{{ $index }}][photographer]" class="form-control"
                                                           placeholder="Photographer" value="{{ $content['photographer'] ?? '' }}">
                                                </div>

                                                <div class="row">
                                                    {{-- Show at Home --}}
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Show at Home</label>
                                                            <select name="contents[{{ $index }}][show_at_home]" class="form-control">
                                                                @foreach(config('helper.yesNo') as $status)
                                                                    <option value="{{ $status['id'] }}"
                                                                        @selected(($content['show_at_home'] ?? 2) == $status['id'])>
                                                                        {{ $status['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Photo of the Day --}}
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Photo of the Day</label>
                                                            <select name="contents[{{ $index }}][photo_of_the_day]" class="form-control">
                                                                @foreach(config('helper.yesNo') as $status)
                                                                    <option value="{{ $status['id'] }}"
                                                                        @selected(($content['photo_of_the_day'] ?? 2) == $status['id'])>
                                                                        {{ $status['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Image --}}
                                            <div class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label>Upload Image:</label>
                                                            <input type="file"
                                                                   name="contents[{{ $index }}][image]"
                                                                   id="contents_{{ $index }}" accept="image/*" class="form-control"
                                                                   onchange="previewImage(this, 'preview_{{ $index }}')">
                                                            @error('contents.' . $index . '.image')<x-error-message :message="$message"/>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group text-right">
                                                            <img src="{{ isset($content['image']) ? asset(Config::get('helper.uploadPath').$content['image']) : asset('adminAssets/no_image.png') }}"
                                                                 id="preview_{{ $index }}"
                                                                 class="img-responsive img-thumbnail"
                                                                 style="width: 100px;" alt="Preview">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Remove Button --}}
                                            <div class="col-sm-1 text-right">
                                                <button type="button" class="btn btn-danger remove-btn"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center" style="margin-bottom: 20px;">
                                <button type="button" id="add_more_photo" class="btn btn-default">Add More Photo</button>
                            </div>

                            {{-- Form Actions --}}
                            <div class="row">
                                <div class="col-xs-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('photo-album.index') }}" class="btn btn-primary pull-left">Back</a>
                                    </div>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!--/.col -->
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('adminAssets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Activate CKEditor
        $(function() {
            $('.ckEditor').each(function(){
                CKEDITOR.replace($(this).attr('id'));
            });
        });

        // Select2
        $('#photo_category_id').select2({ placeholder: "Select category" });
    </script>

    {{-- Add More Photos --}}
    <script>
        const defaultImg = '{{ asset('adminAssets/no_image.png') }}';
        let blockIndex = {{ count($photoAlbum->contents) }};

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add_more_photo').addEventListener('click', addContentBlock);

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    btn.closest('.well').remove();
                });
            });
        });

        function addContentBlock() {
            const wrapper = document.getElementById('photo_container');
            const div = document.createElement('div');
            div.className = 'well mb-2';
            div.innerHTML = `
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" name="contents[${blockIndex}][caption]" required class="form-control" placeholder="Caption">
                        </div>
                        <div class="form-group">
                            <input type="text" name="contents[${blockIndex}][photographer]" required class="form-control" placeholder="Photographer">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Show at Home</label>
                                    <select name="contents[${blockIndex}][show_at_home]" class="form-control">
                                        @foreach (config('helper.yesNo') as $status)
                                            <option value="{{ $status['id'] }}" {{ $status['id'] === 2 ? 'selected' : '' }}>{{ $status['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Photo of the Day</label>
                                    <select name="contents[${blockIndex}][photo_of_the_day]" class="form-control">
                                        @foreach (config('helper.yesNo') as $status)
                                            <option value="{{ $status['id'] }}" {{ $status['id'] === 2 ? 'selected' : '' }}>{{ $status['name'] }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Upload Image:</label>
                                    <input type="file" name="contents[${blockIndex}][image]" required id="contents_${blockIndex}" accept="image/*" class="form-control"
                                        onchange="previewImage(this, 'preview_${blockIndex}')">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group text-right">
                                    <img src="${defaultImg}" id="preview_${blockIndex}"
                                        class="img-responsive img-thumbnail" style="width: 100px;" alt="Preview">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 text-right">
                        <button type="button" class="btn btn-danger remove-btn"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;

            // Append the new div to the wrapper
            wrapper.appendChild(div);
            // Add event listener to remove button
            div.querySelector('.remove-btn').addEventListener('click', () => div.remove());
            // Increment blockIndex for the next block
            blockIndex++;
        }

        // Image preview
        function previewImage(input, targetId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById(targetId).src = e.target.result;
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
