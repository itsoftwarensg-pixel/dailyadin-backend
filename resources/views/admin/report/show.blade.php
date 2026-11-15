@extends('layout.admin')

@section('title', 'User Report - ' . $user->name)

@section('css')
    <style>
        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .stats-box h3 {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
        }

        .stats-box p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content-item {
            padding: 10px;
            border-bottom: 1px solid #f4f4f4;
            transition: background-color 0.2s;
        }

        .content-item:hover {
            background-color: #f9f9f9;
        }

        #loadMoreBtn {
            display: none;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            User Details Report: {{ $user->name }}
            <small>Detailed Statistics</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('report.index') }}"><i class="fa fa-bar-chart"></i> Reports</a></li>
            <li class="active">{{ $user->name }}</li>
        </ol>
    </section>

    <section class="content">
        <!-- User Info -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-aqua-active" style="height: auto !important;">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h5 class="widget-user-desc">{{ $user->role->name ?? 'N/A' }} - {{ $user->email }}</h5>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $contentsCount }}</h5>
                                    <span class="description-text">Created</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $updatesCount }}</h5>
                                    <span class="description-text">Updated</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $createsCount }}</h5>
                                    <span class="description-text">Creates Logged</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <form method="GET" action="{{ route('report.show', $user->id) }}" class="form-inline">
                            <div class="form-group" style="margin-right: 15px;">
                                <label for="start_date" style="margin-right: 5px;">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ $startDate }}" required>
                            </div>

                            <div class="form-group" style="margin-right: 15px;">
                                <label for="end_date" style="margin-right: 5px;">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ $endDate }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Apply
                            </button>

                            <a href="{{ route('report.show', $user->id) }}" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>

                            <div class="pull-right">
                                <a href="{{ route('report.export-user-pdf', [$user->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                    class="btn btn-success" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i> Download
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contents List -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Contents Created by {{ $user->name }} <small class="text-muted">(From
                                {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($startDate) }} To
                                {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($endDate) }})</small>
                        </h3>
                    </div>
                    <div class="box-body" id="contentListContainer">
                        <div id="contentList">
                            <!-- Contents will be loaded here via AJAX -->
                        </div>
                        <div class="text-center" style="margin-top: 20px;">
                            <button id="loadMoreBtn" class="btn btn-primary">
                                <i class="fa fa-refresh"></i> Load More
                            </button>
                            <div id="loadingIndicator" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let currentPage = 1;
            let lastPage = 1;
            const userId = {{ $user->id }};
            const startDate = "{{ $startDate }}";
            const endDate = "{{ $endDate }}";

            // Load initial contents
            loadContents(currentPage);

            // Load more button click
            $('#loadMoreBtn').click(function() {
                if (currentPage < lastPage) {
                    currentPage++;
                    loadContents(currentPage);
                }
            });

            function loadContents(page) {
                $('#loadingIndicator').show();
                $('#loadMoreBtn').hide();

                $.ajax({
                    url: "{{ route('report.contents', $user->id) }}",
                    type: 'GET',
                    data: {
                        page: page,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        $('#loadingIndicator').hide();

                        if (page === 1) {
                            $('#contentList').html(response);
                        } else {
                            $('#contentList').append(response);
                        }

                        // Check if there are more pages (simple check based on response length)
                        // You might want to return pagination metadata from the controller
                        if (response.trim().length > 0) {
                            lastPage = currentPage + 1; // Assume there might be more
                            $('#loadMoreBtn').show();
                        } else {
                            $('#loadMoreBtn').hide();
                        }
                    },
                    error: function(xhr) {
                        $('#loadingIndicator').hide();
                        alert('Error loading contents. Please try again.');
                    }
                });
            }
        });
    </script>
@endsection
