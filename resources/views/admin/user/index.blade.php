@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>All User</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add User</a></li>
            <li class="active">User List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Users</h3>
                        <div class="box-tools">
                            <form method="get">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control input-sm pull-right" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                        <a class="btn btn-sm btn-default" href="{{ route('user.index') }}">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($users) > 0)
                                <table class="table table-condensed table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>User Type</th>
                                        <th>Status</th>
                                        <th>Photo</th>
                                        <th>Member since</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->role->name }}</td>
                                            <td>
                                                @if($user->status == StatusEnum::ACTIVE->value)
                                                    <span class="label label-success">Approved</span>
                                                @else
                                                    <span class="label label-danger">Deactivated</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->photo)
                                                    <img
                                                        src="{{ asset(Config::get('helper.uploadPath') . $user->photo) }}"
                                                        class="img-responsive img-thumbnail" alt="{{ $user->name }}"
                                                        style="height:100px; width:100px">
                                                @else
                                                    <img src="{{asset('adminAssets/default.jpg')}}"
                                                         class="img-responsive img-thumbnail" alt="User Image"
                                                         style="height:100px; width:100px">
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->created_at ? $user->created_at->format('F j, Y h:i:s A') : '' }}
                                            </td>
                                            <td>
                                                @if ($user->trashed())
                                                    <p style="width: 200px" class="text-maroon">The user is deleted temporarily.</p>
                                                    {{-- User Restore --}}
                                                    @if (auth()->user()->role_id == 1)
                                                        <form action="{{ route('userRestore', ['user' => $user->id]) }}"
                                                              method="POST" style="display: inline;">
                                                            @csrf
                                                            <button
                                                                class="btn bg-purple btn-sm btn-block"
                                                                type="submit"
                                                                onclick="return confirm('Are you sure to restore?')"
                                                            >
                                                                Restore
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn btn-default btn-sm">Edit</a> |
                                                    @if (auth()->user()->role_id == 1)
                                                        <form
                                                            action="{{ route('user.destroy', ['user' => $user->id]) }}"
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
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$users->links()}}
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
