@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Sub Category
            <small>All Sub Category</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('sub-category.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Sub Category</a></li>
            <li class="active">Sub Category List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Sub Category</h3>
                        <div class="box-tools">
                            <form method="get">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                        <a class="btn btn-sm btn-default" href="{{ route('sub-category.index') }}">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($subCategories) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>SubCategory Name</th>
                                        <th>Alias</th>
                                        <th>Publish Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($subCategories as $key => $subCategory)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $subCategory->category ? $subCategory->category->name : 'N/A' }}</td>
                                            <td>{{ $subCategory->name }}</td>
                                            <td>{{ $subCategory->alias }}</td>
                                            <td>
                                                @if($subCategory->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('sub-category.edit', ['sub_category' => $subCategory->id]) }}?page={{ request()->get('page', 1) }}"
                                                    class="btn btn-default btn-sm"
                                                >
                                                    Edit
                                                </a>
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('sub-category.destroy', ['sub_category' => $subCategory->id]) }}"
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
                                    {{$subCategories->links()}}
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
