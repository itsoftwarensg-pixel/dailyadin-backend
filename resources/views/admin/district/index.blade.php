@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            District
            <small>All District</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">All District</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @foreach ($divisions as $division)
                                <h4><strong>{{ $division->name }} <small>({{ $division->districts->count() }} Districts)</small></strong></h4>

                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th style="width:200px">District Name</th>
                                        <th style="width:200px">District Name (Bangla)</th>
                                        <th>Alias/Slug</th>
                                        <th style="width:100px;text-align: center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($division->districts as $key => $district)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $district->name }}</td>
                                            <td>{{ $district->name_bn }}</td>
                                            <td>{{ $district->alias }}</td>
                                            <td style="text-align: center">
                                                <a href="{{ route('district.edit', $district->id) }}" class="btn btn-sm btn-default">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No districts available</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
