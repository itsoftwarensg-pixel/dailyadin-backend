@extends('layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            Manage Position
            <small>All Position Types</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage Position</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Position List</h3>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">#</th>
                            <th>Position Type</th>
                            <th>Value</th>
                            <th style="width: 100px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enums as $key => $enum)
                            <tr>
                                <td style="text-align: center;">{{ $key + 1 }}</td>
                                <td><strong>{{ ucwords(str_replace('_', ' ', $enum->value)) }}</strong></td>
                                <td>{{ $enum->value }}</td>
                                <td>
                                    <a href="{{ route('manage-position.show', str_replace('_', '-', $enum->value)) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-cog"></i> Manage
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
