@php use App\Enums\StatusEnum; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Tag
            <small>All Tag</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('tag.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Tag</a></li>
            <li class="active">Tag List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">All Tag</h3>
                        <div class="box-tools">
                            <form method="get">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" name="search" class="form-control input-sm pull-right" value="{{ request('search') }}" placeholder="Search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
                                        <a class="btn btn-sm btn-default" href="{{ route('tag.index') }}">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding" style="padding-bottom: 30px!important;">
                        <li class="col-sm-12">
                            @if (count($tags) > 0)
                                <ul class="list-unstyled row">
                                    @foreach ($tags as $key => $tag)
                                        <li class="col-sm-6">
                                            <div class="list-div with-border">
                                                <div>
                                                    <span>{{ $tag->name }}</span>
                                                    <a href="{{ route('tag.edit', ['tag' => $tag->id]) }}" class="text-blue small"><u>{{ $tag->alias }}</u></a>
                                                </div>
                                                <div class="text-right" style="width:220px">
                                                    <span style="padding:0 10px">({{ $tag->contents_count }} Contents)</span>
                                                    @if($tag->status == StatusEnum::ACTIVE->value)
                                                        <span class="badge active"></span>
                                                    @else
                                                        <span class="badge disabled"></span>
                                                    @endif
                                                    <a href="{{ route('tag.edit', ['tag' => $tag->id]) }}" class="btn text-black btn-sm"><i class="fa fa-pencil"></i></a>
                                                    @if (auth()->user()->role_id == 1)
                                                        <form action="{{ route('tag.destroy', ['tag' => $tag->id]) }}"
                                                              method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="btn text-red btn-sm"
                                                                type="submit"
                                                                onclick="return confirm('Are you sure to delete?')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$tags->links()}}
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

@section('css')
    <style>
        .list-div {
            border: 1px solid #ececec;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1px 5px 1px 10px;
        }
        .list-div:hover{
            background: #ececec;
        }

        .badge.active,
        .badge.disabled{

            height: 10px;
            border-radius: 100%;
            display: inline-block;
            padding: 4px;
            max-width: 10px;
            margin-right: 10px;
        }

        .badge.active{
            background: #06b906;
        }

        .badge.disabled{
            background: gray;
        }
    </style>
@endsection
