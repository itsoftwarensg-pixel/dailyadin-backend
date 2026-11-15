<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>News Report - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3c8dbc;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #3c8dbc;
            font-size: 20px;
        }

        .header p {
            margin: 3px 0;
            color: #666;
        }

        .user-info {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #3c8dbc;
        }

        .user-info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead {
            background-color: #3c8dbc;
            color: white;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            font-weight: bold;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .summary-box {
            margin-top: 15px;
            padding: 8px;
            background-color: #f0f8ff;
            border-left: 4px solid #3c8dbc;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ব্যবহারকারীর নিউজ রিপোর্ট</h1>
        <p><strong>{{ $user->name }}</strong></p>
    </div>

    <div class="user-info">
        <p><strong>পদবী:</strong> {{ $user->role->name ?? '' }}</p>
        <p><strong>ইমেইল:</strong> {{ $user->email }}</p>
        <p><strong>রিপোর্টের সময়কাল:</strong> {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($startDate) }} থেকে
            {{ \App\Helpers\BengaliDateHelper::formatBengaliDate($endDate) }} পর্যন্ত</p>
        <p><strong>মোট নিউজের সংখ্যা:</strong> {{ \App\Helpers\BengaliDateHelper::bnNum(count($contents)) }}</p>
        <p><strong>রিপোর্ট তৈরির সময়:</strong> {{ \App\Helpers\BengaliDateHelper::formatBengaliDateTime(now()) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 8%;" class="text-center">আইডি</th>
                <th style="width: 51%;">শিরোনাম</th>
                <th style="width: 13%;">ক্যাটাগরি</th>
                <th style="width: 23%;">তারিখ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contents as $index => $content)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $content->id }}</td>
                    <td><a href="{{ $content->frontend_url }}" target="_blank"
                            style="text-decoration: none; color: #3c8dbc;">{{ $content->heading }}</a></td>
                    <td><span class="label label-info">{{ $content->category->name ?? 'প্রযোজ্য নয়' }}</span></td>
                    <td>{{ \App\Helpers\BengaliDateHelper::formatBengaliDateTime($content->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                        নির্বাচিত সময়কালের মধ্যে কোনো নিউজ পাওয়া যায়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <strong>সারসংক্ষেপ:</strong> ব্যবহারকারী <strong>{{ $user->name }}</strong>
        <strong>{{ \App\Helpers\BengaliDateHelper::formatBengaliDate($startDate) }}</strong> থেকে
        <strong>{{ \App\Helpers\BengaliDateHelper::formatBengaliDate($endDate) }}</strong> পর্যন্ত সময়ে
        <strong>{{ \App\Helpers\BengaliDateHelper::bnNum(count($contents)) }}টি</strong> নিউজ তৈরি করেছেন।
    </div>

    <div class="footer">
        <p>এটি ডেইলি আদিন সিএমএস থেকে একটি স্বয়ংক্রিয়ভাবে তৈরি রিপোর্ট</p>
        <p>&copy; বঙ্গসফট লি. (https://www.bongosoftbd.com/)</p>
    </div>
</body>

</html>
