@extends('layout.admin')

@section('title', 'User Reports')

@section('css')
    <style>
        .report-card {
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
            margin-bottom: 20px;
        }

        .report-card-header {
            background: #3c8dbc;
            color: #fff;
            padding: 10px 15px;
            font-weight: bold;
        }

        .report-card-body {
            padding: 15px;
        }

        .user-stats-row:hover {
            background-color: #f5f5f5;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            User Reports
            <small>Content Creation & Update Statistics</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">User Reports</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">All Users Statistics</h3>
                    </div>

                    <!-- Filter Form -->
                    <div class="box-body" style="background-color: #f9f9f9; border-bottom: 1px solid #ddd;">
                        <form method="GET" action="{{ route('report.index') }}" class="form-inline">
                            <div class="row">
                                <div class="col-md-12">
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

                                    <div class="form-group" style="margin-right: 15px;">
                                        <label for="user_id" style="margin-right: 5px;">User:</label>
                                        <select name="user_id" id="user_id" class="form-control"
                                            style="max-width: 350px;">
                                            <option value="">All Users</option>
                                            @foreach ($allUsers as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ $userId == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }} ({{ $u->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-filter"></i> Apply
                                    </button>

                                    <a href="{{ route('report.index') }}" class="btn btn-default">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>

                                    <a href="{{ route('report.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'user_id' => $userId]) }}"
                                        class="btn btn-success btn-sm" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i> Download
                                    </a>

                                    <a href="{{ route('report.export-excel', ['start_date' => $startDate, 'end_date' => $endDate, 'user_id' => $userId]) }}"
                                        class="btn btn-success btn-sm">
                                        <i class="fa fa-file-excel-o"></i> Download Excel
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle"></i>
                                    তথ্য প্রদর্শিত হচ্ছে:
                                    <strong>{{ \App\Helpers\BengaliDateHelper::formatBengaliDate($startDate) }}</strong>
                                    থেকে
                                    <strong>{{ \App\Helpers\BengaliDateHelper::formatBengaliDate($endDate) }}</strong>
                                    পর্যন্ত
                                    @if ($userId)
                                        ব্যবহারকারী:
                                        <strong>{{ $allUsers->firstWhere('id', $userId)->name ?? 'Unknown' }}</strong>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>নাম</th>
                                    <th>ইমেইল</th>
                                    <th>পদবী</th>
                                    <th>তৈরিকৃত</th>
                                    <th>আপডেটকৃত</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr class="user-stats-row">
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name ?? 'N/A' }}</td>
                                        <td><span>{{ $user->contents_count }}</span></td>
                                        <td><span>{{ $user->updates_count }}</span></td>
                                        <td>
                                            <a href="{{ route('report.show', [$user->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> বিস্তারিত দেখুন
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
