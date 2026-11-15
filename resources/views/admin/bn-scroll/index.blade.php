@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Scroll News
            <small>All Scroll News</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('bn-scroll.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Scroll News</a></li>
            <li class="active">Scroll News List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Scroll News</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($bnScrolls) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Heading</th>
                                        <th>Scroll Type</th>
                                        <th>End Time</th>
                                        <th>Activity By</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($bnScrolls as $key => $scroll)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $scroll->heading }}</td>
                                            <td>{{ $scroll->type_label }}</td>
                                            <td>
                                                {{ $scroll->end_time ? $scroll->end_time->format('F j, Y h:i:s A') : '' }}
                                            </td>
                                            <td>
                                                Created by: {{ $scroll->creator ? $scroll->creator->name : 'N/A' }}<br>
                                                Updated by: {{ $scroll->updater ? $scroll->updater->name : 'N/A' }}
                                            </td>
                                            <td>
                                                প্রকাশিত: <b class="text-blue">{{ $scroll->created_at ? $scroll->created_at->format('F j, Y h:i:s A') : '' }}</b>
                                            </td>
                                            <td>
                                                <a href="{{ route('bn-scroll.edit', ['bn_scroll' => $scroll->id]) }}" class="btn btn-default btn-sm">Edit</a> |
                                                @if (auth()->user()->role_id == 1)
                                                    <form
                                                        action="{{ route('bn-scroll.destroy', ['bn_scroll' => $scroll->id]) }}"
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
                                    {{$bnScrolls->links()}}
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
