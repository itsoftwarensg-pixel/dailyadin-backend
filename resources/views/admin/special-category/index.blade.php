@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Special Category
            <small>All Special Category</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('special-category.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Special Category</a></li>
            <li class="active">Special Category List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Special Category List</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($specialCategories) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Alias</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($specialCategories as $key => $specialCategory)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $specialCategory->name }}</td>
                                            <td>{{ $specialCategory->alias }}</td>
                                            <td>
                                                @if($specialCategory->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('special-category.edit', ['special_category' => $specialCategory->id]) }}" class="btn btn-default btn-sm">Edit</a>
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('special-category.destroy', ['special_category' => $specialCategory->id]) }}"
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
