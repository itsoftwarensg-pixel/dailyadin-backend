@extends('layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            Manage Special Events
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('manage-position.index') }}"><i class="fa fa-list"></i> Position List</a></li>
            <li class="active">Special Events</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Special Events</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('manage-position.special-event.create') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Create New Event
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Event Name</th>
                                    <th>Event URL</th>
                                    <th width="100">News Count</th>
                                    <th width="100">Status</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($specialEvents as $event)
                                    <tr>
                                        <td>{{ $event->id }}</td>
                                        <td>{{ $event->event_name }}</td>
                                        <td>
                                            @if ($event->event_url)
                                                <a href="{{ $event->event_url }}"
                                                    target="_blank">{{ Str::limit($event->event_url, 50) }}</a>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $event->news_ids ? count(explode(',', $event->news_ids)) : 0 }}
                                        </td>
                                        <td class="text-center">
                                            <button
                                                class="btn btn-xs toggle-status {{ $event->active_status == 1 ? 'btn-success' : 'btn-default' }}"
                                                data-id="{{ $event->id }}" data-status="{{ $event->active_status }}">
                                                {{ $event->active_status == 1 ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{ route('manage-position.special-event.edit', $event->id) }}"
                                                class="btn btn-primary btn-xs">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No special events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(function() {
            $('.toggle-status').click(function() {
                var btn = $(this);
                var eventId = btn.data('id');
                var currentStatus = btn.data('status');

                $.ajax({
                    url: '{{ route('manage-position.special-event.toggle-status', ':id') }}'
                        .replace(':id', eventId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            var newStatus = response.active_status;
                            btn.data('status', newStatus);

                            if (newStatus == 1) {
                                btn.removeClass('btn-default').addClass('btn-success').text(
                                    'Active');
                            } else {
                                btn.removeClass('btn-success').addClass('btn-default').text(
                                    'Inactive');
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
@endsection
