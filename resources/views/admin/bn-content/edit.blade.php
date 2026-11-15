@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/token-input/token-input.css') }}">
    <link rel="stylesheet" href="{{ asset('adminAssets/plugins/token-input/token-input-facebook.css') }}">
@endsection

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Content
            <small>Edit </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('bn-content.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Content</a></li>
            <li class="active">Edit Content</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Content</h3>
                    </div>
                    <form role="form" action="{{ route('bn-content.update', ['bn_content' => $bnContent->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- pagination page carry -->
                        <input type="hidden" name="page" value="{{ request()->get('page') }}">

                        <div class="box-body">
                            <div class="row">
                                {{-- Category --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Content Category</label>
                                        <select name="category_id" id="primaryCategory" class="form-control select2"
                                            style="font-size: 1.1em;" onchange="getSubCatListBN(this);">
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id', $bnContent->category_id) == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>
                                {{-- Previous category id --}}
                                <input type="hidden" name="previous_category_id" value="{{ $bnContent->category_id }}">

                                {{-- Category Home Position --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Home Position</label>
                                        <select name="category_home_position" id="category_home_position"
                                            class="form-control" style="font-size: 1.1em;">
                                            <option value="1" @selected($bnContent->category_home_position == 1)>None</option>
                                            @for ($i = 2; $i <= 16; $i++)
                                                <option value="{{ $i }}" @selected((int) $i == ($categoryHomeIndexPosition !== null ? $categoryHomeIndexPosition : null))>
                                                    Position-{{ $i - 1 }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('category_home_position')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Category Inner Position --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Inner Position</label>
                                        <select name="category_inner_position" id="category_inner_position"
                                            class="form-control" style="font-size: 1.1em;">
                                            <option value="1" @selected($bnContent->category_inner_position == 1)>None</option>
                                            @for ($i = 2; $i <= 6; $i++)
                                                <option value="{{ $i }}" @selected((int) $i == ($categoryInnerIndexPosition !== null ? $categoryInnerIndexPosition : null))>
                                                    Position-{{ $i - 1 }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('category_inner_position')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- ePaper Date --}}
                                <div class="col-sm-12" id="datepickerWrapper"
                                    style="{{ $bnContent->category_id == '51' ? 'display:block' : 'display:none' }}">
                                    <div class="form-group well well-sm">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                @php $ePaperDate = $bnContent->e_paper_date ?? ''; // If null, default to empty @endphp
                                                <label>ePaper Date</label>
                                                <input type="date" id="e_paper_date" name="e_paper_date"
                                                    class="form-control" value="{{ $ePaperDate }}" />
                                                @error('e_paper_date')
                                                    <x-error-message :message="$message" />
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Secondary Category --}}
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Secondary Category</label>
                                        <select name="secondary_category_ids[]" id="secondaryCategory" multiple=""
                                            class="form-control select2" data-select2-id="secondaryCategory" tabindex="-1"
                                            aria-hidden="true">
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category->id }}" @selected(in_array($category->id, $secondaryCategoryIds))>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('secondary_category_ids')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Sub Category --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Sub Category&nbsp;<span class="fa fa-spinner fa-spin ajaxLoader text-danger"
                                                style="display:none;"></span></label>
                                        <select name="sub_category_id" id="subCategory" class="form-control select2"
                                            style="font-size: 1.1em;">
                                            <option value="1">NONE</option>
                                            @foreach ($subCategories as $key => $subCategory)
                                                <option value="{{ $subCategory->id }}" @selected($bnContent->sub_category_id == $subCategory->id)>
                                                    {{ $subCategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sub_category_id')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sub Category Position --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Sub Category Position</label>
                                        <select name="sub_category_id_pos" class="form-control" style="font-size: 1.1em;">
                                            <option value="1" @selected($bnContent->sub_category_id_pos == 1)>None</option>
                                            @for ($i = 2; $i <= 10; $i++)
                                                <option value="{{ $i }}" @selected($bnContent->sub_category_id_pos == $i)>
                                                    Top-{{ $i - 1 }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('sub_category_id_pos')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- District --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>District</label>
                                        <select name="district_id" id="district" class="form-control select2"
                                            style="font-size: 1.1em;" onchange="getUpazliaListByDistrict(this);">
                                            <option value="">-- জেলা --</option>
                                            @foreach ($districts as $key => $district)
                                                <option value="{{ $district->id }}" @selected($bnContent->district_id == $district->id)>
                                                    {{ $district->name_bn }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('district_id')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Special Category --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Special Category</label>
                                        <select name="special_category_id" id="specialCategory" class="form-control"
                                            style="font-size: 1.1em;">
                                            @foreach ($specialCategories as $key => $category)
                                                <option value="{{ $category->id }}" @selected($bnContent->special_category_id == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('special_category_id')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Special Category Position --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Special Category Position</label>
                                        <select name="special_category_position" class="form-control"
                                            style="font-size: 1.1em;">
                                            <option value="1" @selected($bnContent->special_category_position == 1)>None</option>
                                            @for ($i = 2; $i <= 13; $i++)
                                                <option value="{{ $i }}" @selected((int) $i == ($leadIndexPosition !== null ? $leadIndexPosition : null))>
                                                    Position-{{ $i - 1 }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('special_category_position')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Upazila --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Upazila&nbsp;<span class="fa fa-spinner fa-spin ajaxLoader text-danger"
                                                style="display:none;"></span></label>
                                        <select name="upazila_id" id="upazila" class="form-control select2"
                                            style="font-size: 1.1em;">
                                            <option value="">--উপজেলা--</option>
                                            @foreach ($upazilas as $key => $upazila)
                                                <option value="{{ $upazila->id }}" @selected($bnContent->upazila_id == $upazila->id)>
                                                    {{ $upazila->name_bn }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('upazila_id')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Sub Heading --}}
                            <div class="form-group">
                                <label for="sub_heading">Content Sub Heading</label>
                                <input type="text" class="form-control" id="sub_heading" name="sub_heading"
                                    value="{{ $bnContent->sub_heading }}">
                                @error('sub_heading')
                                    <x-error-message :message="$message" />
                                @enderror
                            </div>

                            {{-- Heading/Headline/Title/New Title --}}
                            <div class="form-group">
                                <label for="heading">Content Heading &nbsp;<small class="text-red"><i
                                            class="fa fa-asterisk"></i></small></label>
                                <input type="text" class="form-control" id="heading" name="heading"
                                    value="{{ $bnContent->heading }}" style="font-size:16px;" required>
                                @error('heading')
                                    <x-error-message :message="$message" />
                                @enderror
                            </div>

                            {{-- Tags --}}
                            <div class="form-group token-input-custom-wrapper">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="tags">Tags</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-right">
                                            <a href="{{ route('tag.create') }}" target="_blank"
                                                class="btn btn-success btn-xs">Add Tag</a>
                                            <a href="{{ route('tag.index') }}" target="_blank"
                                                class="btn btn-info btn-xs">All Tags</a>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="tags" name="tags"
                                    style="width:100%">
                                @error('tags')
                                    <x-error-message :message="$message" />
                                @enderror
                            </div>

                            {{-- Listed Writer --}}
                            <div class="row">
                                {{-- Writer --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="writer_id">Listed Writer</label>
                                        <select name="writer_id" id="writer" class="form-control select2"
                                            style="font-size: 1.1em;">
                                            @foreach ($writers as $key => $writer)
                                                <option value="{{ $writer->id }}" @selected($bnContent->writer_id == $writer->id)>
                                                    {{ $writer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('writer_id')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Guest Writer --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="writers">Guest Writer</label>
                                        <input type="text" class="form-control" id="writers" name="writers"
                                            value="{{ $bnContent->writers }}">
                                        @error('writers')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>

                                {{-- Initial --}}
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="initial">Initial</label>
                                        <input type="text" class="form-control" id="initial" name="initial"
                                            value="{{ $bnContent->initial }}">
                                        @error('initial')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Brief/Excerpt/Short Description --}}
                            <div class="form-group">
                                <label for="content_brief" style="cursor: pointer;" data-toggle="collapse"
                                    data-target="#briefBox">
                                    Brief/Intro (Click to Expand) <i class="fa fa-angle-down"></i>
                                </label>
                                @error('content_brief')
                                    <x-error-message :message="$message" />
                                @enderror
                                <div id="briefBox" class="collapse {{ $bnContent->content_brief ? 'in' : '' }}">
                                    <textarea class="form-control" id="content_brief" name="content_brief" placeholder="Short Brief"
                                        style="resize: vertical; min-height: 100px;">{{ $bnContent->content_brief }}</textarea>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="form-group">
                                <label for="content_details">Description</label>
                                @error('content_details')
                                    <x-error-message :message="$message" />
                                @enderror
                                <textarea class="form-control ckEditor" id="content_details" name="content_details" placeholder="Description"
                                    style="resize: vertical; min-height: 400px;">{{ $bnContent->content_details }}</textarea>
                            </div>

                            <div class="row">
                                {{-- Featured Image --}}
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="bg_image_path">Image</label>
                                        <input type="file" id="bg_image_path" name="bg_image_path"
                                            onchange="getPreview('bg_image_path','photoPreview', 'none')">
                                        <p class="help-block text-red">Max 600KB. (1200x630)</p>
                                        @error('bg_image_path')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>

                                    {{-- Image caption --}}
                                    <div class="form-group">
                                        <label for="bg_image_caption">Image Caption</label>
                                        <input type="text" class="form-control" id="bg_image_caption"
                                            name="bg_image_caption" value="{{ $bnContent->bg_image_caption }}">
                                        @error('bg_image_caption')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>

                                    {{-- Video path --}}
                                    <div class="form-group">
                                        <label for="video_path">Video Path</label>
                                        <input type="text" class="form-control" id="video_path" name="video_path"
                                            value="{{ $bnContent->video_path }}">
                                        @error('video_path')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>

                                    {{-- Sound path --}}
                                    <div class="form-group">
                                        <label for="sound_path">Sound Path</label>
                                        <input type="text" class="form-control" id="sound_path" name="sound_path"
                                            value="{{ $bnContent->sound_path }}">
                                        @error('sound_path')
                                            <x-error-message :message="$message" />
                                        @enderror
                                    </div>
                                </div>
                                {{-- Image preview --}}
                                <div class="col-md-4">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview</label>
                                        <img src="{{ $bnContent->bg_image_path ? asset(Config::get('helper.uploadPath') . $bnContent->bg_image_path) : asset('adminAssets/no_image.png') }}"
                                            class="img-responsive img-thumbnail" id="photoPreview" alt="Preview"
                                            title="Default Image" style="width: 100%" />
                                    </div>
                                </div>
                            </div>

                            {{-- Meta --}}
                            <div>
                                {{-- Meta Title --}}
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        placeholder="Meta title" value="{{ $bnContent->meta_title }}">
                                    @error('meta_title')
                                        <x-error-message :message="$message" />
                                    @enderror
                                </div>

                                {{-- Meta Keywords --}}
                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                        placeholder="Meta keywords" value="{{ $bnContent->meta_keywords }}">
                                    @error('meta_keywords')
                                        <x-error-message :message="$message" />
                                    @enderror
                                </div>

                                {{-- Meta Description --}}
                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    @error('meta_description')
                                        <x-error-message :message="$message" />
                                    @enderror
                                    <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description"
                                        style="resize: vertical; min-height: 100px;">{{ $bnContent->meta_description }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Show Content --}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="show_content">Show Content?</label>
                                        <div class="well well-sm">
                                            <div class="radio" style="display:flex">
                                                @foreach (config('helper.yesNo') as $status)
                                                    <label class="radio-inline" style="width:50px">
                                                        <input type="radio" value="{{ $status['id'] }}"
                                                            class="minimal" name="show_content"
                                                            @checked($bnContent->show_content == $status['id']) />
                                                        {{ $status['name'] }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Show In Scroll --}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="show_in_scroll">Show In Scroll?</label>
                                        <div class="well well-sm">
                                            <div class="radio" style="display:flex">
                                                @foreach (config('helper.yesNo') as $status)
                                                    <label class="radio-inline" style="width:50px">
                                                        <input type="radio" value="{{ $status['id'] }}"
                                                            class="minimal" name="show_in_scroll"
                                                            @checked($bnContent->show_in_scroll == $status['id']) />
                                                        {{ $status['name'] }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Show In Latest --}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="show_in_latest">Show In Latest</label>
                                        <div class="well well-sm">
                                            <div class="radio" style="display:flex">
                                                @foreach (config('helper.yesNo') as $status)
                                                    <label class="radio-inline" style="width:50px">
                                                        <input type="radio" value="{{ $status['id'] }}"
                                                            class="minimal" name="show_in_latest"
                                                            @checked($bnContent->show_in_latest == $status['id']) />
                                                        {{ $status['name'] }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Show In Mobile --}}
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="show_in_mobile">Show In Mobile</label>
                                        <div class="well well-sm">
                                            <div class="radio" style="display:flex">
                                                @foreach (config('helper.yesNo') as $status)
                                                    <label class="radio-inline" style="width:50px">
                                                        <input type="radio" value="{{ $status['id'] }}"
                                                            class="minimal" name="show_in_mobile"
                                                            @checked($bnContent->show_in_mobile == $status['id']) />
                                                        {{ $status['name'] }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-6 text-left">
                                        <a href="{{ route('bn-content.index') }}" class="btn btn-primary btn-lg">Back</a>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="{{ route('bn-content.cancel', ['bn_content' => $bnContent->id]) }}"
                                            class="btn btn-warning btn-lg">Cancel Editing</a>
                                        <button type="submit" class="btn btn-success btn-lg">Update</button>
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
    {{-- START Token Input for search Tag --}}
    <script src="{{ asset('adminAssets/plugins/token-input/jquery.tokeninput.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Convert PHP tags collection to JS array
            var prepopulateTags = {!! $bnContent->tags->isNotEmpty()
                ? $bnContent->tags->map(fn($tag) => ['id' => $tag->id, 'name' => $tag->name])->toJson()
                : '[]' !!};

            // Use url to serach. Ex: https://myurl/tag-serach
            $("#tags").tokenInput(
                '{{ route('tagSearch') }}', //
                {
                    prePopulate: prepopulateTags, // pre-populate the input with tags. Output format: [{id: 1, name: 'tag1'}, {id: 2, name: 'tag2'}]
                    minChars: 3,
                    preventDuplicates: true,
                }
            );
        });
    </script>

    {{-- Initialize Select2 --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    {{-- Add CKEditor --}}
    <script src="{{ asset('adminAssets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('.ckEditor').each(function() {
                CKEDITOR.replace($(this).attr('id'), {
                    height: '500px',
                    filebrowserUploadUrl: "{{ route('cke-upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                });
            });
        });
    </script>

    {{-- AJAX, Get subcategory list based on catgeory change --}}
    <script>
        function getSubCatListBN(categorySelect) {
            console.log('categorySelect', categorySelect);
            var categoryId = categorySelect.value;
            var url = "{{ route('getSubcategoriesByCategory') }}";

            $.get(url, {
                category_id: categoryId,
            }, function(subCategories) {
                var options = '<option value="1">NONE</option>';

                if (subCategories.length > 0) {
                    subCategories.forEach(function(sub) {
                        options += `<option value="${sub.id}">${sub.name}</option>`;
                    });
                }

                $("#subCategory").html(options);
            }, 'json');
        }
    </script>

    {{-- Primary, Secondary category on change --}}
    <script>
        // Initialize Select2
        $(document).ready(function() {
            // Run function on page load to disable primary in secondary
            updateSecondaryCategory();

            // Run when primary or secondary category changes
            $('#primaryCategory, #secondaryCategory').on('change', function() {
                toggleDatepicker();
                updateSecondaryCategory();
            });
        });

        function updateSecondaryCategory() {
            let primaryCategory = $('#primaryCategory').val();
            let secondaryCategory = $('#secondaryCategory');

            // Get current selected values in secondary category
            let selectedValues = secondaryCategory.val() || [];

            // Remove primary category from secondary category if it exists
            if (selectedValues.includes(primaryCategory)) {
                selectedValues = selectedValues.filter(value => value !== primaryCategory);
                secondaryCategory.val(selectedValues).trigger('change'); // Update Select2 UI
            }

            // Enable all options first
            secondaryCategory.find('option').prop('disabled', false);

            // Disable the newly selected primary category in secondary category
            secondaryCategory.find(`option[value="${primaryCategory}"]`).prop('disabled', true);
        }
    </script>

    {{-- ePaper datepicker toggle --}}
    <script>
        function toggleDatepicker() {
            const categoryVal = $("#primaryCategory").val();
            const $wrapper = $("#datepickerWrapper");
            const $ePaperInput = $("#e_paper_date");

            console.log('toggleDatepicker', categoryVal);

            if (categoryVal === "51") {
                $wrapper.show();
                // $ePaperInput.prop("required", true); // Add required
            } else {
                $wrapper.hide();
                // $ePaperInput.prop("required", false); // Remove required
            }
        }
    </script>

    {{-- AJAX, Get upazila list based on district change --}}
    <script>
        function getUpazliaListByDistrict(districtSelect) {
            console.log('districtSelect', districtSelect);
            var districtId = districtSelect.value;
            var url = "{{ route('getUpazilaByDistrict') }}";

            $.get(url, {
                district_id: districtId,
            }, function(upazilas) {
                var options = '<option value="1">NONE</option>';

                if (upazilas.length > 0) {
                    upazilas.forEach(function(upazila) {
                        options += `<option value="${upazila.id}">${upazila.name_bn}</option>`;
                    });
                }

                $("#upazila").html(options);
            }, 'json');
        }
    </script>

    {{-- Custome URL Box --}}
    <script>
        $(document).ready(function() {
            let CustomUrlBox = document.getElementById("CustomUrlBox");
            $("input[name='is_custom_url']").change(function() {
                var radioValue = $("input[name='is_custom_url']:checked").val();
                if (radioValue == 1) {
                    CustomUrlBox.style.display = 'block'
                } else {
                    CustomUrlBox.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        // Run on page load
        $(document).ready(function() {
            toggleDatepicker();
        });
    </script>
@endsection
