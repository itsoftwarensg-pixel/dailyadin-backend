@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Webstory
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('webstory.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Webstory</a></li>
            <li class="active">Edit Webstory</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- form start -->
                <form role="form" action="{{ route('webstory.update', $webstory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Webstory</h3>
                        </div>
                        <div class="box-body">
                            <div class="box-body">
                                {{-- Main Webstory Section --}}
                                <div class="">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            {{-- Category --}}
                                            <div class="form-group">
                                                <label for="webstory_category_id">Category Name</label>
                                                <select
                                                    name="webstory_category_id"
                                                    id="webstory_category_id"
                                                    class="form-control @error('webstory_category_id') is-invalid @enderror"
                                                >
                                                    <option value="">Select Category</option>
                                                    @foreach($webstoryCategories as $category)
                                                        <option value="{{ $category->id }}" {{ (old('webstory_category_id', $webstory->webstory_category_id) == $category->id) ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('webstory_category_id')<x-error-message :message="$message"/>@enderror
                                            </div>

                                            {{-- Title --}}
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input
                                                    type="text"
                                                    class="form-control @error('title') is-invalid @enderror"
                                                    id="title"
                                                    name="title"
                                                    placeholder="Webstory Main Title"
                                                    value="{{ old('title', $webstory->title) }}"
                                                />
                                                @error('title')<x-error-message :message="$message"/>@enderror
                                            </div>

                                            {{-- Credit By --}}
                                            <div class="form-group">
                                                <label for="credit_by">Credit By</label>
                                                <input
                                                    type="text"
                                                    class="form-control @error('credit_by') is-invalid @enderror"
                                                    id="credit_by"
                                                    name="credit_by"
                                                    placeholder="Credit By"
                                                    value="{{ old('credit_by', $webstory->credit_by) }}"
                                                />
                                                @error('credit_by')<x-error-message :message="$message"/>@enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label for="image">Webstory Main Image</label>
                                                        <input
                                                            type="file"
                                                            id="image"
                                                            name="image"
                                                            class="@error('image') is-invalid @enderror"
                                                            onchange="previewImage(this,'bgImg')"
                                                        />
                                                        <p class="help-block text-yellow">
                                                            Dimension: <span class="text-red">500x840px</span> |
                                                            Image must be smaller than <span class="text-red">300KB</span>
                                                        </p>
                                                        @error('image')<x-error-message :message="$message"/>@enderror
                                                    </div>
                                                    <div class="form-group" style="margin-top: 75px;">
                                                        <label for="status">Status</label>
                                                        <select id="status" class="form-control" name="status">
                                                            @foreach (config('helper.status') as $status)
                                                                <option value="{{ $status['id'] }}" @selected($webstory->status == $status['id'])>
                                                                    {{ $status['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('status')<x-error-message :message="$message"/>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group text-right">
                                                        <img
                                                            src="{{ old('image', $webstory->image ? asset(Config::get('helper.uploadPath') . $webstory->image) : asset('adminAssets/no_image.png')) }}"
                                                            id="bgImg"
                                                            alt="Main Image"
                                                            class="img-responsive img-thumbnail"
                                                            style="width: 100px"
                                                        />
                                                    </div>
                                                </div>
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
                    </div>
                    <div class="box no-border">
                        <div class="box-body" style="padding: 20px;">
                            {{-- Dynamic Webstory Contents --}}
                            <div id="webstory_container">
                                @php
                                    $contents = $webstory->contents;
                                @endphp

                                @foreach($contents as $index => $content)
                                    <div class="well mb-2" id="content-{{ $index }}">
                                        {{-- Hidden input for $content['id']  ---> ($webstoryContent->id) --}}
                                        <input type="hidden" name="contents[{{ $index }}][id]" value="{{ $content['id'] }}">
                                        {{-- Keep old image if no new one is uploaded --}}
                                        <input type="hidden" name="contents[{{ $index }}][content_hidden_image]" value="{{ $content['image'] }}">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" name="contents[{{ $index }}][caption]" class="form-control @error('contents.' . $index . '.caption') is-invalid @enderror"
                                                           placeholder="Caption" value="{{ $content['caption'] ?? '' }}">
                                                    @error('contents.' . $index . '.caption')<x-error-message :message="$message"/>@enderror
                                                </div>
                                                <div class="form-group">
                                                        <textarea name="contents[{{ $index }}][description]" rows="3"
                                                                  class="form-control @error('contents.' . $index . '.description') is-invalid @enderror"
                                                                  placeholder="Description">{{ $content['description'] ?? '' }}</textarea>
                                                    @error('contents.' . $index . '.description')<x-error-message :message="$message"/>@enderror
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
                                                            <p class="help-block text-yellow">
                                                                Dimension: <span class="text-red">500x840px</span> |  Max <span class="text-red">300KB</span>
                                                            </p>
                                                            @error('contents.' . $index . '.image')<x-error-message :message="$message"/>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group text-right">
                                                            <img src="{{ asset(Config::get('helper.uploadPath') . $content['image']) ?? asset('adminAssets/no_image.png') }}"
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
                            </div>

                            <div class="text-center" style="margin-bottom: 20px;">
                                <button type="button" id="add_more_webstory" class="btn btn-default">Add More Webstory</button>
                            </div>

                            {{-- Form Actions --}}
                            <div class="row">
                                <div class="col-xs-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('webstory.index') }}" class="btn btn-primary pull-left">Back</a>
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
            </div><!-- /.box -->
        </div><!--/.col -->
    </section>
@endsection

@section('js')
    {{-- Initialize Select2 --}}
    <script>
        $(document).ready(function () {
            $('#webstory_category_id').select2({
                placeholder: "Select category",
            });
        });
    </script>

    {{-- Dynamic Webstory Contents --}}
    <script type="text/javascript">

        const defaultImg = '{{ asset('adminAssets/no_image.png') }}';
        let blockIndex = {{ count($webstory->contents) }};

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add_more_webstory').addEventListener('click', addContentBlock);

            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    btn.closest('.well').remove();
                });
            });
        });

        function addContentBlock() {
            const wrapper = document.getElementById('webstory_container');
            const div = document.createElement('div');
            div.className = 'well mb-2';
            div.innerHTML = `
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" name="contents[${blockIndex}][caption]" required class="form-control" placeholder="Caption">
                        </div>

                        <div class="form-group">
                            <textarea name="contents[${blockIndex}][description]" rows="3" required class="form-control" placeholder="Description"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Upload Image:</label>
                                    <input type="file" name="contents[${blockIndex}][image]" required id="contents_${blockIndex}" accept="image/*" class="form-control" onchange="previewImage(this, 'preview_${blockIndex}')">
                                    <p class="help-block text-yellow">
                                        Dimension: <span class="text-red">500x840px</span> |  Max <span class="text-red">300KB</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 text-right">
                                <img src="${defaultImg}" id="preview_${blockIndex}"
                                     class="img-responsive img-thumbnail" style="width:100px;" alt="Preview">
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

