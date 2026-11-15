@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Photo Album
            <small>Add New </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('photo-album.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Photo Album</a></li>
            <li class="active">Add New Photo Album</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- form start -->
                <form role="form" action="{{ route('photo-album.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add New Photo Album</h3>
                        </div>

                        {{-- Main Photo Album Fields --}}
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
                                                    <option value="{{ $category->id }}" {{ old('photo_category_id') == $category->id ? 'selected' : '' }}>
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
                                                placeholder="Album name"
                                                value="{{ old('name') }}"
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
                                                placeholder="Photographer"
                                                value="{{ old('photographer_name') }}"
                                            />
                                            @error('photographer_name')<x-error-message :message="$message"/>@enderror
                                        </div>

                                        {{-- Main - Ablum Image --}}
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
                                                    {{--<p class="help-block text-yellow">
                                                        Dimension: <span class="text-red">500x840px</span> |
                                                        Image must be smaller than <span class="text-red">300KB</span>
                                                    </p>--}}
                                                    @error('image')<x-error-message :message="$message"/>@enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group text-right">
                                                    <img
                                                        src="{{ asset('adminAssets/no_image.png') }}"
                                                        id="bgImg"
                                                        alt="Default Image"
                                                        class="img-responsive img-thumbnail"
                                                        style="width: 100px"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        {{-- Description --}}
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            @error('description')<x-error-message :message="$message"/>@enderror
                                            <textarea class="form-control ckEditor" id="description" name="description" placeholder="Description Here" style="resize: vertical; min-height: 100px;">{{ old('description') }}</textarea>
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
                            {{-- Dynamic Photo Album Contents --}}
                            <div id="photo_container">
                                @if(old('contents'))
                                    @foreach(old('contents') as $index => $content)
                                        <div class="well mb-2" id="content-{{ $index }}">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" name="contents[{{ $index }}][caption]" class="form-control @error('contents.' . $index . '.caption') is-invalid @enderror"
                                                               placeholder="Caption" value="{{ $content['caption'] ?? '' }}">
                                                        @error('contents.' . $index . '.caption')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" name="contents[{{ $index }}][photographer]" class="form-control @error('contents.' . $index . '.photographer') is-invalid @enderror"
                                                               placeholder="Photographer" value="{{ $content['photographer'] ?? '' }}">
                                                        @error('contents.' . $index . '.photographer')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Show at Home</label>
                                                                <select name="contents[{{ $index }}][show_at_home]" class="form-control @error('contents.' . $index . '.show_at_home') is-invalid @enderror">
                                                                    @foreach (config('helper.yesNo') as $status)
                                                                        <option value="{{ $status['id'] }}"
                                                                            @selected(isset($content['show_at_home']) && $content['show_at_home'] == $status['id'])>
                                                                            {{ $status['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('contents.' . $index . '.show_at_home')<x-error-message :message="$message"/>@enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Photo of the Day</label>
                                                                <select name="contents[{{ $index }}][photo_of_the_day]" class="form-control @error('contents.' . $index . '.photo_of_the_day') is-invalid @enderror">
                                                                    @foreach (config('helper.yesNo') as $status)
                                                                        <option value="{{ $status['id'] }}"
                                                                            @selected(isset($content['photo_of_the_day']) && $content['photo_of_the_day'] == $status['id'])>
                                                                            {{ $status['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('contents.' . $index . '.photo_of_the_day')<x-error-message :message="$message"/>@enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="form-group">
                                                                <label>Upload Image:</label>
                                                                <input type="file" name="contents[{{ $index }}][image]"
                                                                       id="contents_{{ $index }}"
                                                                       accept="image/*"
                                                                       class="form-control @error('contents.' . $index . '.image') is-invalid @enderror"
                                                                       onchange="previewImage(this, 'preview_{{ $index }}')">
                                                                @error('contents.' . $index . '.image')<x-error-message :message="$message"/>@enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group text-right">
                                                                <img src="{{ $content['image'] ?? asset('adminAssets/no_image.png') }}"
                                                                     id="preview_{{ $index }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     style="width: 100px;"
                                                                     alt="Preview">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-1 text-right">
                                                    <button type="button" class="btn btn-danger remove-btn"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
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
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.box -->
        </div><!--/.col -->
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

    {{-- Initialize Select2 --}}
    <script>
        $(document).ready(function () {
            $('#photo_category_id').select2({
                placeholder: "Select category",
            });
        });
    </script>

    @php
        $defaultImg = asset('adminAssets/no_image.png');
        $oldContents = old('contents', []);
    @endphp

    {{-- Dynamic Photo Album Contents --}}
    <script type="text/javascript">
        // Start blockIndex from old contents count if validation failed
        let blockIndex = {{ count($oldContents) }};
        const defaultImg = '{{ $defaultImg }}';

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add_more_photo').addEventListener('click', addContentBlock);

            // Attach remove event to any old blocks
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const wrapper = document.getElementById('photo_container');
                    wrapper.removeChild(btn.closest('.well'));
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
                        <input type="text" name="contents[${blockIndex}][caption]" class="form-control" placeholder="Caption">
                    </div>
                    <div class="form-group">
                        <input type="text" name="contents[${blockIndex}][photographer]" class="form-control" placeholder="Photographer">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Show at Home</label>
                                <select name="contents[${blockIndex}][show_at_home]" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="2" selected>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Photo of the Day</label>
                                <select name="contents[${blockIndex}][photo_of_the_day]" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="2" selected>No</option>
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
                                <input
                                    type="file"
                                    name="contents[${blockIndex}][image]"
                                    id="contents_${blockIndex}"
                                    accept="image/*"
                                    class="form-control"
                                    onchange="previewImage(this, 'preview_${blockIndex}')"
                                />
                                <!--<p class="help-block text-yellow">
                                    Dimension: <span class="text-red">500x840px</span> |
                                    Image must be smaller than <span class="text-red">300KB</span>
                                </p>-->
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group text-right">
                                <img
                                    src="${defaultImg}"
                                    id="preview_${blockIndex}"
                                    class="img-responsive img-thumbnail"
                                    style="width: 100px;"
                                    alt="Preview"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-1 text-right">
                    <button type="button" class="btn btn-danger remove-btn"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        `;

            wrapper.appendChild(div);

            // Attach remove event for this new block
            div.querySelector('.remove-btn').addEventListener('click', function () {
                wrapper.removeChild(div);
            });

            blockIndex++;
        }

        // Image preview function
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

