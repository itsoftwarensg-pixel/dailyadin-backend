<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>User Reports - {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($startDate) }} to
        {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($endDate) }}</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3c8dbc;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #3c8dbc;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-box {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #3c8dbc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #3c8dbc;
            color: white;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            font-weight: bold;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-green {
            background-color: #00a65a;
            color: white;
        }

        .badge-blue {
            background-color: #3c8dbc;
            color: white;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ইউজার অ্যাক্টিভিটি রিপোর্ট</h1>
        <p><strong>কন্টেন্ট তৈরি ও আপডেট পরিসংখ্যান</strong></p>
    </div>

    <div class="info-box">
        <p><strong>রিপোর্টের সময়কাল:</strong> {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($startDate) }} থেকে
            {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($endDate) }} পর্যন্ত</p>
        @if ($userId)
            <p><strong>ব্যবহারকারী:</strong> {{ $allUsers->firstWhere('id', $userId)->name ?? '' }}</p>
        @else
            <p><strong>আওতা:</strong> সকল ব্যবহারকারী</p>
        @endif
        <p><strong>রিপোর্ট তৈরির সময়:</strong> {{ \App\Helpers\BengaliDateHelper::formatBengaliDateTime(now()) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 40%;">নাম</th>
                <th style="width: 18%;">পদবী</th>
                <th style="width: 16%; text-align: center;">তৈরিকৃত</th>
                <th style="width: 16%; text-align: center;">আপডেটকৃত</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role->name ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        <span>{{ $user->contents_count }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span>{{ $user->updates_count }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="no-data">নির্বাচিত সময়কালের জন্য কোনো তথ্য পাওয়া যায়নি।</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($users->count() > 0)
        <div style="margin-top: 20px; padding: 10px; background-color: #f0f8ff; border-left: 4px solid #3c8dbc;">
            <p style="margin: 0;"><strong>সারসংক্ষেপ:</strong></p>
            <p style="margin: 5px 0 0 0;">
                মোট ব্যবহারকারী: <strong>{{ \App\Helpers\BengaliDateHelper::bnNum($users->count()) }}</strong> |
                তৈরিকৃত কন্টেন্ট:
                <strong>{{ \App\Helpers\BengaliDateHelper::bnNum($users->sum('contents_count')) }}</strong> |
                আপডেটকৃত কন্টেন্ট:
                <strong>{{ \App\Helpers\BengaliDateHelper::bnNum($users->sum('updates_count')) }}</strong>
            </p>
        </div>
    @endif

    <div class="footer">
        <p>এটি দৈনিক এদিন সিএমএস থেকে একটি স্বয়ংক্রিয়ভাবে তৈরি রিপোর্ট</p>
        <p>&copy; বঙ্গসফট লি. (https://www.bongosoftbd.com/)</p>
    </div>
</body>

</html>
