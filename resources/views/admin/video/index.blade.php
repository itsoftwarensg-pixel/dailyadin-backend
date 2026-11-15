@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Video
            <small>All Video</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('video.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Video</a></li>
            <li class="active">Video List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Video</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($videos) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Embed Code</th>
                                        <th>Photo</th>
                                        <th>Status</th>
                                        <th style="width: 116px;text-align: center">Action</th>
                                    </tr>
                                    @foreach ($videos as $key => $video)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $video->title }}</td>
                                            <td>{{ $video->embed_code }}</td>
                                            <td>
                                                @if($video->embed_code)
                                                    <img
                                                        src="https://img.youtube.com/vi/{{$video->embed_code}}/hqdefault.jpg"
                                                        class="img-responsive img-thumbnail"
                                                        alt="{{ $video->title }}" style="height:100px;"
                                                    >
                                                @endif
                                            </td>
                                            <td>
                                                @if($video->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('video.edit', ['video' => $video->id]) }}" class="btn btn-default btn-sm">Edit</a>
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('video.destroy', ['video' => $video->id]) }}"
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
                                    {{$videos->links()}}
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
