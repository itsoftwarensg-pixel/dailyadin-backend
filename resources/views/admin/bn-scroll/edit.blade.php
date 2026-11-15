@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Scroll News
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('bn-scroll.index') }}"><i class="fa fa-dashboard fa-fw"></i>All  Scroll News</a></li>
            <li class="active">Edit Scroll News</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Scroll News</h3>
                    </div>

                    <!-- form start -->
                    <form role="form" action="{{ route('bn-scroll.update', ['bn_scroll' => $bnScroll->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Scroll Type</label>
                                        <select class="form-control" name="type">
                                            <option value="">--Select Type--</option>
                                            @foreach($scrollTypes as $scrollType)
                                                <option value="{{ $scrollType['id'] }}"
                                                    @selected($bnScroll->type?->value == $scrollType['id'])> {{--$bnScroll->type?->value, here `value` indicates specific Enum--}}
                                                    {{ $scrollType['type'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="end_time">End Time</label>
                                        <input type="datetime-local" class="form-control" id="end_time" name="end_time" placeholder="Enter end time" value="{{ $bnScroll->end_time }}">
                                        @error('end_time')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="heading">Heading</label>
                                        <textarea class="form-control" id="heading" name="heading" placeholder="Scroll Heading" style="resize: vertical; min-height: 200px;">{{$bnScroll->heading}}</textarea>
                                        @error('heading')<x-error-message :message="$message"/>@enderror
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer text-center">
                            <a href="{{ route('bn-scroll.index') }}" class="btn btn-primary pull-left">Back</a>
                            <button type="submit" class="btn btn-success pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
