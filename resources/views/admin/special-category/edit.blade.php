@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Special Category
            <small>Edit </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('special-category.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Special Category</a></li>
            <li class="active">Edit Special Category</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Special Category</h3>
                    </div>

                    <form role="form" action="{{ route('special-category.update', ['special_category' => $specialCategory->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ $specialCategory->name }}">
                                @error('name')<x-error-message :message="$message"/>@enderror
                            </div>
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label for="alias">Alias</label>
                                        <input type="text" class="form-control" id="alias" name="alias" placeholder="Enter special category alias. e.g. special-category-alias" value="{{ $specialCategory->alias }}">
                                        @error('alias')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="sorting">Priority</label>
                                        <input type="text" class="form-control" id="sorting" name="sorting" placeholder="Priority" value="{{ $specialCategory->sorting }}">
                                        @error('sorting')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                @error('description')<x-error-message :message="$message"/>@enderror
                                <textarea class="form-control ckEditor" id="description" name="description" placeholder="Description" style="resize: vertical; min-height: 100px;">{{ $specialCategory->description }}</textarea>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Show Special Category</label>
                                        <select id="status" class="form-control" name="status">
                                            @foreach (config('helper.yesNo') as $status)
                                                <option value="{{ $status['id'] }}" @selected($specialCategory->status == $status['id'])>
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
                                        <a href="{{ route('special-category.index') }}" class=" btn btn-primary pull-left">Back</a>
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

