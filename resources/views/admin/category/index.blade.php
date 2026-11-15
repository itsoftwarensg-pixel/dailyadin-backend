@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Category
            <small>All Category</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('category.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Category</a></li>
            <li class="active">Category List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Category</h3>
                        <div class="box-tools">
                            <form method="get">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                        <a class="btn btn-sm btn-default" href="{{ route('category.index') }}">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($categories) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Alias</th>
                                        <th>Publish Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->alias }}</td>
                                            <td>
                                                @if($category->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('category.edit', ['category' => $category->id]) }}?page={{ request()->get('page', 1) }}"
                                                    class="btn btn-default btn-sm">Edit</a>
                                                {{--
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('category.destroy', ['category' => $category->id]) }}"
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
                                                @endif--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$categories->links()}}
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
