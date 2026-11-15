@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Photo Album
            <small>All Photo Album</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('photo-album.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Photo Album</a></li>
            <li class="active">Photo Album List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Photo Album</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($photoAlbums) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Album Name</th>
                                        <th>Description</th>
                                        <th>Photographer</th>
                                        <th>Status</th>
                                        <th style="width:120px">Image</th>
                                        <th style="width: 116px;text-align: center">Action</th>
                                    </tr>
                                    @foreach ($photoAlbums as $key => $photoAlbum)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $photoAlbum->category ? $photoAlbum->category->name : '' }}</td>
                                            <td>{{ $photoAlbum->name }}</td>
                                            <td>{!! $photoAlbum->description !!}</td>
                                            <td>{{ $photoAlbum->photographer_name }}</td>
                                            <td>
                                                @if($photoAlbum->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($photoAlbum->image)
                                                    <img src="{{ asset(Config::get('helper.uploadPath') . $photoAlbum->image) }}" class="img-responsive img-thumbnail" alt="{{ $photoAlbum->title }}" style="width:100px; min-height:100px;">
                                                @else
                                                    <img src="{{asset('adminAssets/no_image.png')}}" class="img-responsive img-thumbnail" alt="Writer Photo" style="height:100px; width:100px">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('photo-album.edit', ['photo_album' => $photoAlbum->id]) }}" class="btn btn-default btn-sm">Edit</a>
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('photo-album.destroy', ['photo_album' => $photoAlbum->id]) }}" method="POST" style="display: inline;">
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
                                    {{$photoAlbums->links()}}
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
