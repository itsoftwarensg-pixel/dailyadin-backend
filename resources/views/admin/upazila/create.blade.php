@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Upazila
            <small>Create</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('upazila.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Upazila</a></li>
            <li class="active">Create Upazila</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Upazila</h3>
                    </div>

                    <form role="form" action="{{ route('upazila.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="division">Division</label>
                                        <select class="form-control" id="division" onchange="filterDistricts()">
                                            <option value="">-- Select Division --</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="district_id">District</label>
                                        <select class="form-control" id="district_id" name="district_id">
                                            <option value="">-- Select District --</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}" data-division-id="{{ $district->division_id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('district_id')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name">Upazila Name (English)</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                        @error('name')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name_bn">Upazila Name (Bangla)</label>
                                        <input type="text" class="form-control" name="name_bn" value="{{ old('name_bn') }}">
                                        @error('name_bn')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="alias">Upazila Alias/Slug</label>
                                        <input type="text" class="form-control" name="alias" value="{{ old('alias') }}">
                                        @error('alias')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta title" value="{{ old('meta_title') }}">
                                @error('meta_title')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Meta keywords" value="{{ old('meta_keywords') }}">
                                @error('meta_keywords')<x-error-message :message="$message"/>@enderror
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                @error('meta_description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" style="resize: vertical; min-height: 100px;">{{ old('meta_description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description">Category Description</label>
                                @error('description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control ckEditor" id="description" name="description" placeholder="Category Description Here" style="resize: vertical; min-height: 100px;">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Featured Image</label>
                                        <input type="file" id="photo" name="photo" onchange="getPreview('photo','photoPreview', 'none')">
                                        <p class="help-block text-yellow">Dimension: <span class="text-red">1280x720px</span>, Max <span class="text-red">300KB</span></p>
                                        @error('photo')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <label for="photoPreview">Preview Image : &nbsp;</label>
                                        <img src="{{ asset('adminAssets/no_image.png') }}" class="img-responsive img-thumbnail" id="photoPreview" alt="Preview" title="Image" style="height: 100px;" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center">
                                    <div class="box-footer">
                                        <a href="{{ route('upazila.index') }}" class=" btn btn-primary pull-left">Back</a>
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

        function filterDistricts() {
            var divisionId = document.getElementById('division').value;
            var options = document.querySelectorAll('#district_id option[data-division-id]');

            options.forEach(function(option) {
                var matches = !divisionId || option.getAttribute('data-division-id') === divisionId;
                option.hidden = !matches;
                if (!matches && option.selected) {
                    option.selected = false;
                }
            });

            document.getElementById('district_id').value = '';
        }
    </script>
@endsection
