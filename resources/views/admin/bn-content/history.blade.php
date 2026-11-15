@php
    use App\Helpers\BengaliDateHelper;
@endphp
@extends('layout.admin')

@section('css')
    <style>
        .history-card {
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: none;
            margin-bottom: 25px;
        }

        .history-card .box-header {
            border-bottom: 1px solid #f4f4f4;
            padding: 15px 20px;
        }

        .history-card .box-title {
            font-weight: 600;
            color: #333;
        }

        .timeline {
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }

        .timeline>li>.timeline-item {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            border: 1px solid #eee;
            background: #fff;
            margin-top: 5px;
            padding: 0;
        }

        .timeline>li>.timeline-item>.timeline-header {
            border-bottom: 1px solid #f9f9f9;
            color: #555;
            font-size: 14px;
            padding: 12px 15px;
            line-height: 1.1;
        }

        .timeline>li>.timeline-item>.timeline-body {
            padding: 15px;
        }

        .timeline-label span {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .change-table {
            margin-bottom: 0;
            font-size: 13px;
            border-radius: 6px;
            overflow: hidden;
        }

        .change-table thead th {
            background-color: #fcfcfc;
            border-bottom: 2px solid #eee !important;
            color: #777;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
        }

        .change-table td {
            vertical-align: top !important;
            padding: 10px 12px !important;
        }

        .field-name {
            color: #3c8dbc;
            font-weight: 600;
        }

        .old-val {
            color: #dd4b39;
            background: #fff5f5;
            border-radius: 4px;
            padding: 2px 4px;
        }

        .new-val {
            color: #00a65a;
            background: #f5fff9;
            border-radius: 4px;
            padding: 2px 4px;
        }

        .action-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 5px;
        }

        .bg-create {
            background-color: #3c8dbc !important;
            color: #fff !important;
        }

        .bg-update {
            background-color: #f39c12 !important;
            color: #fff !important;
        }

        .bg-delete {
            background-color: #dd4b39 !important;
            color: #fff !important;
        }

        .bg-restore {
            background-color: #00a65a !important;
            color: #fff !important;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Content History
            <small>Content ID: <span class="text-primary">#{{ $bnContent->id }}</span></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('bn-content.index') }}"><i class="fa fa-list"></i> Content List</a></li>
            <li class="active">History</li>
        </ol>
    </section>

    <section class="content">
        <div class="box history-card">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-file-text-o text-muted shadow-sm" style="margin-right: 8px;"></i>
                    {{ $bnContent->heading }}
                </h3>
            </div>
            <div class="box-body" style="background-color: #fbfbfb; padding: 25px;">
                <ul class="timeline">
                    @foreach ($bnContent->histories as $history)
                        <li class="time-label">
                            <span class="bg-blue shadow-sm">
                                <i class="fa fa-calendar-o"></i>
                                {{ BengaliDateHelper::formatBengaliDateTime($history->created_at) }}
                            </span>
                        </li>
                        <li>
                            @php
                                $iconClass = 'fa-info bg-gray';
                                $badgeClass = 'bg-gray';
                                if ($history->action == 'create') {
                                    $iconClass = 'fa-plus bg-blue';
                                    $badgeClass = 'bg-create';
                                } elseif ($history->action == 'update') {
                                    $iconClass = 'fa-pencil bg-yellow';
                                    $badgeClass = 'bg-update';
                                } elseif ($history->action == 'delete') {
                                    $iconClass = 'fa-trash bg-red';
                                    $badgeClass = 'bg-delete';
                                } elseif ($history->action == 'restore') {
                                    $iconClass = 'fa-refresh bg-green';
                                    $badgeClass = 'bg-restore';
                                }
                            @endphp

                            <i class="fa {{ $iconClass }}"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">
                                    <strong class="text-blue">{{ $history->user->name ?? 'System' }}</strong>
                                    <span class="action-badge {{ $badgeClass }}">{{ $history->action }}</span>
                                    {{-- <span class="text-muted small pull-right">
                                        <i class="fa fa-clock-o"></i> {{ $history->created_at->format('h:i A') }}
                                    </span> --}}
                                </h3>

                                @if ($history->changes && count($history->changes) > 0)
                                    <div class="timeline-body">
                                        <table class="table table-bordered table-hover change-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%">Field Name</th>
                                                    <th style="width: 42.5%">Previous State</th>
                                                    <th style="width: 42.5%">Updated State</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($history->changes as $field => $change)
                                                    @php
                                                        $resolveValue = function ($field, $val) use ($idToNameMap) {
                                                            if (is_null($val) || $val === '') {
                                                                return '<em>(Empty)</em>';
                                                            }

                                                            if (
                                                                in_array($field, [
                                                                    'category_id',
                                                                    'sub_category_id',
                                                                    'special_category_id',
                                                                    'writer_id',
                                                                    'district_id',
                                                                    'upazila_id',
                                                                ])
                                                            ) {
                                                                return $idToNameMap[$field][$val] ?? "ID: $val";
                                                            }

                                                            if (
                                                                in_array($field, [
                                                                    'created_by',
                                                                    'updated_by',
                                                                    'editing_by_id',
                                                                ])
                                                            ) {
                                                                return $idToNameMap['user_id'][$val] ?? "User ID: $val";
                                                            }

                                                            if (in_array($field, ['tag_ids', 'TagIDs'])) {
                                                                $tagIds = explode(',', $val);
                                                                $names = [];
                                                                foreach ($tagIds as $tid) {
                                                                    if (is_numeric($tid)) {
                                                                        $names[] =
                                                                            $idToNameMap['TagIDs'][$tid] ?? "#$tid";
                                                                    }
                                                                }
                                                                return !empty($names)
                                                                    ? implode(', ', $names)
                                                                    : "IDs: $val";
                                                            }

                                                            if ($field === 'secondary_category_ids') {
                                                                $catIds = explode(',', $val);
                                                                $names = [];
                                                                foreach ($catIds as $cid) {
                                                                    if (is_numeric($cid)) {
                                                                        $names[] =
                                                                            $idToNameMap['category_id'][$cid] ??
                                                                            "#$cid";
                                                                    }
                                                                }
                                                                return !empty($names)
                                                                    ? implode(', ', $names)
                                                                    : "IDs: $val";
                                                            }

                                                            return is_array($val)
                                                                ? json_encode($val, JSON_UNESCAPED_UNICODE)
                                                                : $val;
                                                        };
                                                    @endphp
                                                    <tr>
                                                        <td class="field-name"><i class="fa fa-tag text-muted"></i>
                                                            {{ ucwords(str_replace('_', ' ', $field)) }}</td>
                                                        <td class="old-val">
                                                            {!! $resolveValue($field, $change['old']) !!}
                                                        </td>
                                                        <td class="new-val">
                                                            {!! $resolveValue($field, $change['new']) !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach

                    @if (!$hasCreateAction)
                        <li class="time-label">
                            <span class="bg-purple shadow-sm">
                                <i class="fa fa-calendar-check-o"></i>
                                {{ BengaliDateHelper::formatBengaliDateTime($bnContent->created_at) }}
                            </span>
                        </li>
                        <li>
                            <i class="fa fa-plus bg-purple"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">
                                    <strong
                                        class="text-blue">{{ $bnContent->creator->name ?? ($bnContent->created_by ?? 'N/A') }}</strong>
                                    <span class="action-badge bg-create">Initial Creation</span>
                                    <span class="text-muted small pull-right">
                                        <i class="fa fa-history"></i> Legacy Row
                                    </span>
                                </h3>
                                <div class="timeline-body text-center text-muted">
                                    <p style="margin: 0; font-style: italic;">This content was created before history
                                        tracking was fully active.</p>
                                </div>
                            </div>
                        </li>
                    @endif

                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                </ul>
            </div>
            <div class="box-footer" style="padding: 15px 25px; background-color: #fff; border-top: 1px solid #f4f4f4;">
                <a href="{{ route('bn-content.index') }}" class="btn btn-default btn-sm shadow-sm">
                    <i class="fa fa-arrow-left"></i> Back to Content List
                </a>
            </div>
        </div>
    </section>
@endsection
