@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Poll
            <small>All Poll</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('poll.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Poll</a></li>
            <li class="active">Poll List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Poll</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($polls) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th style="width: 116px;text-align: center">Action</th>
                                    </tr>
                                    @foreach ($polls as $key => $poll)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{!! $poll->question_bn !!}</td>
                                            <td>
                                                @if($poll->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('poll.edit', ['poll' => $poll->id]) }}" class="btn btn-default btn-sm">Edit</a>
                                                 |
                                                @if (auth()->user()->role_id == 1)
                                                    <form action="{{ route('poll.destroy', ['poll' => $poll->id]) }}"
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
                                    {{$polls->links()}}
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
