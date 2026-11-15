@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Webstory
            <small>All Webstory</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('webstory.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Webstory</a></li>
            <li class="active">Webstory List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Webstory</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($webstories) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th style="width:120px">WebStory Image</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th style="width: 116px;text-align: center">Action</th>
                                    </tr>
                                    @foreach ($webstories as $key => $webstory)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if($webstory->image)
                                                    <img src="{{ asset(Config::get('helper.uploadPath') . $webstory->image) }}" class="img-responsive img-thumbnail" alt="{{ $webstory->title }}" style="width:100px">
                                                @else
                                                    <img src="{{asset('adminAssets/no_image.png')}}" class="img-responsive img-thumbnail" alt="Writer Photo" style="height:100px; width:100px">
                                                @endif
                                            </td>
                                            <td>{{ $webstory->title }}</td>
                                            <td>
                                                @if($webstory->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('webstory.edit', ['webstory' => $webstory->id]) }}" class="btn btn-default btn-sm">Edit</a>
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('webstory.destroy', ['webstory' => $webstory->id]) }}"
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="btn btn-danger btn-sm"
                                                            type="submit"
                                                            onclick="return confirm('Are you sure to delete?')"
                                                        >
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$webstories->links()}}
                                </div>
                            @else
                                @include('admin.partial.data-not-found')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
