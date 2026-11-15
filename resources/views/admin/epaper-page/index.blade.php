@php use App\Helpers\CustomHelpers; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            ePaper Page
            <small>All ePaper Page</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('epaper-page.create') }}"><i class="fa fa-file-o"></i>Add New</a></li>
            <li class="active">ePaper List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">All ePaper Page</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($epaperPages) > 0)
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($epaperPages as $key => $epaperPage)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ $epaperPage->path }}" class="link-black">{{ $epaperPage->path }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('epaper-page.edit', ['epaper_page' => $epaperPage->id]) }}" class="btn btn-default btn-sm">Edit</a>
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

