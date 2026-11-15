@php use App\Enums\StatusEnum;use App\Helpers\BengaliDateHelper; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Content
            <small>All Uneditable Content</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('bn-content.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Content</a></li>
            <li class="active">Uneditable Content List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <h3 class="box-title">Uneditable Content info</h3>
                            <div class="text-center">
                                <form class="form-inline" style="display: flex; gap: 4px; align-items: center">
                                    <div class="form-group">
                                        <label for="type">Search By <span class="text-red">*</span></label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="1" {{ request('type') == 1 ? 'selected' : '' }}>Content ID</option>
                                            <option value="2" {{ request('type') == 2 ? 'selected' : '' }}>Content Text</option>
                                            <option value="3" {{ request('type') == 3 ? 'selected' : '' }}>Content Writer</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="text" id="text" value="{{ request('text') }}" placeholder="Search content here">
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Search">
                                    <a class="btn btn-default margin-r-5" href="{{ route('uneditableList') }}">Reset</a>
                                </form>
                            </div>
                            <div style="display: flex; gap: 4px; align-items: center">
                                <form method="GET" action="{{ route('uneditableList') }}" id="categorySearchForm" class="form-inline">
                                    <div class="form-group">
                                        <select class="form-control" name="category_id" id="category" onchange="searchByCategory()">
                                            <option value="">All Category</option>
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                                <a class="btn btn-bitbucket margin-r-5" href="{{ route('bn-content.index') }}">All Content</a>
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($bnContents) > 0)
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th style="width:35%">Content Heading</th>
                                        <th>Word Count</th>
                                        <th>Total Hit</th>
                                        <th style="width:20%">Status</th>
                                        <th class="text-center" style="width: 140px">Action</th>
                                    </tr>
                                    @foreach ($bnContents as $key => $bnContent)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                Category:&nbsp;<a
                                                    href="{{ route('bn-content.index', ['category_id' => $bnContent->category->id]) }}">{{ $bnContent->category->name }}</a><br>
                                                Sub
                                                Category:&nbsp;{{ optional($bnContent->subCategory)->name ?? 'N/A' }}
                                                <br>
                                                Writer ID:&nbsp;{{ $bnContent->writer_id }}, Name by
                                                ID: {{ optional($bnContent->writer)->name ?? 'N/A' }}<br>
                                                Writer:&nbsp;{{ $bnContent->writers }}<br>
                                                Content ID:&nbsp;<span class="text-red">{{ $bnContent->id }}</span>
                                            </td>

                                            <td>
                                                <a href="{{rtrim(config('app.frontend_url'), '/') . '/' . ($bnContent->category->alias ?? 'news') . '/' . $bnContent->id}}">
                                                    {{ $bnContent->heading }}
                                                </a>
                                                @if($bnContent->tags)
                                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;margin-top: 4px;">
                                                        @foreach($bnContent->tags as $tag)
                                                            <span style="border:1px solid gray; font-size:10px; padding:1px 4px; border-radius: 10px;">{{ $tag->name ?? 'No Name' }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $bnContent->content_details_word_count }}</td>
                                            <td>{{ optional($bnContent->totalHits)->total_hits ?? 0 }}</td>
                                            <td>
                                                আপডেট:&nbsp;<strong class="text-success">{{ $bnContent->updated_at ? BengaliDateHelper::formatBengaliDateTime($bnContent->updated_at) : ''}}</strong><br>
                                                প্রকাশিত:&nbsp;<strong class="text-light-blue">{{ $bnContent->created_at? BengaliDateHelper::formatBengaliDateTime($bnContent->created_at) : ''}}</strong><br>

                                                সন্নিবেশিত:&nbsp;<strong
                                                    class="text-primary">{{ optional($bnContent->creator)->name ?? $bnContent->created_by }}</strong>&nbsp;|&nbsp;
                                                আপডেট:&nbsp;<strong class="text-success">
                                                    {{ optional($bnContent->updater)->name ?? $bnContent->updated_by }}
                                                </strong>
                                            </td>
                                            <td style="display: flex; justify-content: space-between;gap: 4px; flex-direction: column">
                                                @if ((auth()->user()->role_id == 1) || (auth()->user()->role_id == 2))
                                                    @if ($bnContent->editable == 2)
                                                        <div class="progress" style="height: 70px">
                                                            <div
                                                                class="progress-bar progress-bar-warning progress-bar-striped active"
                                                                role="progressbar" style="width: 100%; padding: 6px 0;">
                                                                    <span class="modal-title"
                                                                          style="display: block;"><b>Editing by </b>
                                                                        {{ optional($bnContent->editingBy)->name ?? 'Editor '.$bnContent->editing_by_id }}
                                                                    </span>
                                                            </div>
                                                        </div>

                                                        {{-- Admin can cancel editing. Also loggoed-in user can cancel only his/her editing content--}}
                                                        @if(auth()->user()->role_id == 1 || auth()->user()->id == optional($bnContent->editingBy)->id)
                                                            <form
                                                                action="{{ route('cancelEditing', ['bn_content' => $bnContent->id]) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                <button
                                                                    class="btn btn-success btn-block btn-flat btn-xs"
                                                                    type="submit">
                                                                    Make Editable
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('bn-content.edit', ['bn_content' => $bnContent->id]) }}"
                                                           class="btn btn-info btn-block btn-flat btn-xs">
                                                            <i class="fa fa-pencil-square-o fa-fw" title="Edit"
                                                               aria-hidden="true"></i>
                                                            Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('bn-content.destroy', ['bn_content' => $bnContent->id]) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="btn btn-danger btn-block btn-flat btn-xs"
                                                                type="submit"
                                                                onclick="return confirm('Are you sure to delete?')"
                                                            >
                                                                Unpublish
                                                            </button>
                                                        </form>
                                                        <button data-id="" class="btn btn-xs btn-block">
                                                            Editing Summary
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$bnContents->links()}}
                                </div>
                            @else
                                @include('admin.partial.data-not-found')
                            @endif
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function searchByCategory() {
            document.querySelector('#categorySearchForm').submit();
        }
    </script>
@endsection
