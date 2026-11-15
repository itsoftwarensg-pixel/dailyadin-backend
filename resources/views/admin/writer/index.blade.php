@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>All Writer</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('writer.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Writer</a></li>
            <li class="active">Writer List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Writer</h3>
                        <div class="box-tools">
                            <form method="get">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                        <a class="btn btn-sm btn-default" href="{{ route('writer.index') }}">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($writers) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th style="width:120px">Photo</th>
                                        <th>Name</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($writers as $key => $writer)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if($writer->photo)
                                                    <img src="{{ asset(Config::get('helper.uploadPath') . $writer->photo) }}" class="img-responsive img-thumbnail" alt="{{ $writer->name }}" style="height:100px; width:100px">
                                                @else
                                                    <img src="{{asset('adminAssets/default.jpg')}}" class="img-responsive img-thumbnail" alt="Writer Photo" style="height:100px; width:100px">
                                                @endif
                                            </td>
                                            <td>{{ $writer->name }}</td>
                                            <td>{{ $writer->title }}</td>
                                            <td>
                                                @if($writer->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('writer.edit', ['writer' => $writer->id]) }}" class="btn btn-default btn-sm">Edit</a> |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('writer.destroy', ['writer' => $writer->id]) }}"
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
                                    {{$writers->links()}}
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
