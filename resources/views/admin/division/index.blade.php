@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Division
            <small>All Division</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">All Division</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($divisions) > 0)
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Division Name (Bangla)</th>
                                        <th>Division Name (English)</th>
                                        <th>Slug</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($divisions as $key => $division)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $division->name_bn }}</td>
                                            <td>{{ $division->name }}</td>
                                            <td>{{ $division->alias }}</td>
                                            <td>
                                                <a href="{{ route('division.edit', ['division' => $division->id]) }}" class="btn btn-default btn-sm">Edit</a>
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
