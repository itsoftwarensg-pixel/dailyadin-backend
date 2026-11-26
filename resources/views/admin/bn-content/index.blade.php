@php
    use App\Enums\StatusEnum;
    use App\Helpers\BengaliDateHelper;
@endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Content
            <small>All Content</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('bn-content.create') }}"><i class="fa fa-dashboard fa-fw"></i>Add Content</a></li>
            <li class="active">Content List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <h3 class="box-title">Content Info Table</h3>
                            <div class="text-center">
                                <form class="form-inline" style="display: flex; gap: 4px; align-items: center">
                                    <div class="form-group">
                                        <label for="type">Search By <span class="text-red">*</span></label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="1" {{ request('type') == 1 ? 'selected' : '' }}>Content ID
                                            </option>
                                            <option value="2" {{ request('type') == 2 ? 'selected' : '' }}>Content Text
                                            </option>
                                            <option value="3" {{ request('type') == 3 ? 'selected' : '' }}>Content
                                                Writer</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="text" id="text"
                                            value="{{ request('text') }}" placeholder="Search content here">
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name="show_content" id="show_content">
                                            <option value="">--Status--</option>
                                            <option value="1" {{ request('show_content') == 1 ? 'selected' : '' }}>
                                                Published</option>
                                            <option value="2" {{ request('show_content') == 2 ? 'selected' : '' }}>
                                                Pending</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name="category_id" id="category">
                                            <option value="">All Category</option>
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category->id }}"
                                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="submit" class="btn btn-primary" value="Search">
                                    <a class="btn btn-default margin-r-5" href="{{ route('bn-content.index') }}">Reset</a>

                                    <a href="{{ route('uneditableList') }}" class="progress"
                                        style="height: 34px;margin-bottom: 0;border-radius: 2px">
                                        <div class="progress-bar progress-bar-yellow progress-bar-striped active"
                                            style="width: 100%; padding: 7px 10px;">
                                            <b style="display: block;">
                                                Uneditable Content List
                                            </b>
                                        </div>
                                    </a>
                                </form>
                            </div>
                            {{-- <div>
                                <form method="GET" action="{{ route('bn-content.index') }}" id="categorySearchForm" class="form-inline">
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
                            </div> --}}
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
                                                        @if(!empty($bnContent->secondary_categories))
                                                            Secondary Categories:
                                                            @foreach($bnContent->secondary_categories as $sec)
                                                                <a href="{{ route('bn-content.index', ['category_id' => $sec['id']]) }}">{{ $sec['name'] }}</a>@if(!$loop->last), @endif
                                                            @endforeach
                                                            <br>
                                                        @endif
                                                    Sub
                                                    Category:&nbsp;{{ optional($bnContent->subCategory)->name ?? 'N/A' }}
                                                    <br>
                                                    Writer ID:&nbsp;{{ $bnContent->writer_id }}, Name by
                                                    ID: {{ optional($bnContent->writer)->name ?? 'N/A' }}<br>
                                                    Writer:&nbsp;{{ $bnContent->writers }}<br>
                                                    Content ID:&nbsp;<span class="text-red">{{ $bnContent->id }}</span>
                                                </td>

                                                <td>
                                                    <a href="{{ rtrim(config('app.frontend_url'), '/') . '/' . ($bnContent->category->alias ?? 'news') . '/' . $bnContent->id }}"
                                                        target="_blank">
                                                        {{ $bnContent->heading }}
                                                    </a>
                                                    @if ($bnContent->tags)
                                                        <div
                                                            style="display: flex; flex-wrap: wrap; gap: 4px;margin-top: 4px;">
                                                            @foreach ($bnContent->tags as $tag)
                                                                <span
                                                                    style="border:1px solid gray; font-size:10px; padding:1px 4px; border-radius: 10px;">{{ $tag->name ?? 'No Name' }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $bnContent->content_details_word_count }}</td>
                                                <td>{{ optional($bnContent->totalHits)->total_hits ?? 0 }}</td>
                                                <td>
                                                    আপডেট:&nbsp;<strong
                                                        class="text-success">{{ $bnContent->updated_at ? BengaliDateHelper::formatBengaliDateTime($bnContent->updated_at) : '' }}</strong><br>
                                                    প্রকাশিত:&nbsp;<strong
                                                        class="text-light-blue">{{ $bnContent->created_at ? BengaliDateHelper::formatBengaliDateTime($bnContent->created_at) : '' }}</strong><br>

                                                    সন্নিবেশিত:&nbsp;<strong class="text-primary">
                                                        {{-- {{ $bnContent->created_by_old ?? 'N/A' }} --}}
                                                        {{ optional($bnContent->creator)->name ?? $bnContent->created_by }}
                                                    </strong>&nbsp;|&nbsp;
                                                    আপডেট:&nbsp;<strong class="text-success">
                                                        {{ optional($bnContent->updater)->name ?? $bnContent->updated_by }}
                                                    </strong>
                                                </td>
                                                <td
                                                    style="display: flex; justify-content: space-between;gap: 4px; flex-direction: column">

                                                    @if ($bnContent->editable == 2)
                                                        <div class="progress" style="height: 70px">
                                                            <div class="progress-bar progress-bar-warning progress-bar-striped active"
                                                                role="progressbar" style="width: 100%; padding: 6px 0;">
                                                                <span class="modal-title" style="display: block;"><b>Editing
                                                                        by </b>
                                                                    {{ optional($bnContent->editingBy)->name ?? 'Editor ' . $bnContent->editing_by_id }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{--
                                                            Admin can cancel editing.
                                                            Also loggoed-in user can cancel only his/her editing content
                                                        --}}
                                                        @if (auth()->user()->role_id == 1 || auth()->user()->id == optional($bnContent->editingBy)->id)
                                                            <form
                                                                action="{{ route('bn-content.cancel', ['bn_content' => $bnContent->id]) }}"
                                                                method="POST" style="display: inline;">
                                                                @csrf
                                                                <button class="btn btn-success btn-block btn-flat btn-xs"
                                                                    type="submit">
                                                                    Make Editable
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('bn-content.edit', ['bn_content' => $bnContent->id]) }}?page={{ request()->get('page', 1) }}"
                                                            class="btn btn-info btn-block btn-flat btn-xs">
                                                            <i class="fa fa-pencil-square-o fa-fw" title="Edit"
                                                                aria-hidden="true"></i>
                                                            Edit
                                                        </a>

                                                        {{-- Only for 1=Admin, 6=Senior_Sub_Editor --}}
                                                        @if ((auth()->user()->role_id == 1) || (auth()->user()->role_id == 6))
                                                            <button
                                                                class="btn btn-block btn-flat btn-xs toggle-status {{ $bnContent->show_content == 1 ? 'btn-danger' : 'btn-success' }}"
                                                                data-id="{{ $bnContent->id }}"
                                                                data-status="{{ $bnContent->show_content }}">
                                                                {{ $bnContent->show_content == 1 ? 'Unpublish' : 'Publish' }}
                                                            </button>
                                                        @endif
                                                        @if ((auth()->user()->role_id == 1))
                                                            <button
                                                                class="btn btn-xs btn-block btn-default show-editing-summary"
                                                                data-id="{{ $bnContent->id }}"
                                                                data-history-url="{{ route('bn-content.history', $bnContent->id) }}">
                                                                <i class="fa fa-history"></i> Editing Summary
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
                                    {{ $bnContents->links() }}
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

    <!-- Editing Summary Modal -->
    <div class="modal fade" id="editingSummaryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 4px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Content History : <span id="modalContentId"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr style="background-color: #f9f9f9;">
                                    <th style="width: 50px;">#</th>
                                    <th>Time</th>
                                    <th>Updated By (Edit)</th>
                                    <th>Created By (Insert)</th>
                                </tr>
                            </thead>
                            <tbody id="editingSummaryBody">
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"
                    style="display: flex; justify-content: flex-end; gap: 8px; align-items: center;">
                    <a href="#" id="completeHistoryBtn" class="btn btn-primary btn-sm">Complete History</a>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function searchByCategory() {
            document.querySelector('#categorySearchForm').submit();
        }
    </script>

    {{-- Content Publish/Unpublish --}}
    <script>
        $(function() {
            $('.toggle-status').click(function() {
                var btn = $(this);
                var contentId = btn.data('id');
                var currentStatus = btn.data('status');
                var currentStatusText = currentStatus == 1 ? 'Unpublish' : 'Publish';

                // Alert before AJAX call
                if (!confirm('Are you sure you want to ' + currentStatusText + ' the content?')) {
                    return; // User cancelled
                }

                $.ajax({
                    url: '{{ route('bn-content.toggle-status', ':id') }}'.replace(':id',
                        contentId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            var newStatus = response.show_content;
                            btn.data('status', newStatus);

                            if (newStatus == 1) {
                                btn.removeClass('btn-success').addClass('btn-danger').text(
                                    'Unpublish');
                            } else {
                                btn.removeClass('btn-danger').addClass('btn-success').text(
                                    'Publish');
                            }
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Failed to toggle status');
                    }
                });
            });
        });
    </script>

    {{-- Editing Summary Modal Logic --}}
    <script>
        $(function() {
            $('.show-editing-summary').click(function() {
                var btn = $(this);
                var contentId = btn.data('id');
                var historyUrl = btn.data('history-url');
                var summaryUrl = '{{ route('bn-content.summary', ':id') }}'.replace(':id', contentId);

                $('#modalContentId').text(contentId);
                $('#completeHistoryBtn').attr('href', historyUrl);
                $('#editingSummaryBody').html(
                    '<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>'
                );
                $('#editingSummaryModal').modal('show');

                $.ajax({
                    url: summaryUrl,
                    type: 'GET',
                    success: function(response) {
                        var html = '';
                        if (response.histories && response.histories.length > 0) {
                            $.each(response.histories, function(index, history) {
                                html += '<tr>';
                                html += '<td>' + (index + 1) + '</td>';
                                html += '<td>' + history.time + '</td>';
                                html += '<td>' + history.updated_by + '</td>';
                                html += '<td>' + history.created_by + '</td>';
                                html += '</tr>';
                            });
                        } else {
                            html =
                                '<tr><td colspan="4" class="text-center">No history found</td></tr>';
                        }
                        $('#editingSummaryBody').html(html);
                    },
                    error: function(xhr) {
                        $('#editingSummaryBody').html(
                            '<tr><td colspan="4" class="text-center text-red">Failed to load summary</td></tr>'
                        );
                    }
                });
            });
        });
    </script>
@endsection
