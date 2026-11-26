<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BnContent;
use App\Services\SimpleXlsxExporter;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class ReportController extends Controller
{
    /**
     * Display a listing of users with their content statistics.
     */
    public function index(Request $request)
    {
        // Default to yesterday
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', $startDate);
        $userId = $request->input('user_id');

        // Build the query
        $query = User::query();

        // Always exclude specific admin/test users
        $query->whereNotIn('email', ['bogdadishahali@gmail.com', 'admin@bongosoftbd.com']);

        // Filter by specific user if provided
        if ($userId) {
            $query->where('id', $userId);
        }

        // Apply date range filters to content counts
        $query->withCount([
            'contents as contents_count' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            },
            'histories as updates_count' => function ($q) use ($startDate, $endDate) {
                $q->where('action', 'update')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
        ])
            ->with('role')
            ->orderBy('contents_count', 'desc');

        $users = $query->paginate(20)->withQueryString();

        // Get all users for the filter dropdown (also excluding admin/test users)
        $allUsers = User::whereNotIn('email', ['bogdadishahali@gmail.com', 'admin@bongosoftbd.com'])
            ->orderBy('name')
            ->get();

        return view('admin.report.index', compact('users', 'allUsers', 'startDate', 'endDate', 'userId'));
    }

    /**
     * Display the specified user's detailed statistics.
     */
    public function show(Request $request, User $user)
    {
        // Default to yesterday
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', $startDate);

        // Get counts within date range
        $contentsCount = $user->contents()
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $updatesCount = $user->histories()
            ->where('action', 'update')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $createsCount = $user->histories()
            ->where('action', 'create')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        return view('admin.report.show', compact('user', 'contentsCount', 'updatesCount', 'createsCount', 'startDate', 'endDate'));
    }

    /**
     * Return paginated contents for a user (for AJAX lazy loading).
     */
    public function userContents(Request $request, User $user)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $user->contents()
            ->with(['category', 'writer', 'totalHits'])
            ->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $contents = $query->paginate(15);

        // Return partial view for AJAX
        if ($request->ajax()) {
            return view('admin.report.content-list', compact('contents'))->render();
        }

        return view('admin.report.content-list', compact('contents'));
    }

    /**
     * Export the report as PDF with applied filters.
     */
    public function exportPdf(Request $request)
    {
        // Default to last 7 days (weekly)
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $userId = $request->input('user_id');

        // Build the query (same as index)
        $query = User::query();

        // Always exclude specific admin/test users
        $query->whereNotIn('email', ['bogdadishahali@gmail.com', 'admin@bongosoftbd.com']);

        // Filter by specific user if provided
        if ($userId) {
            $query->where('id', $userId);
        }

        // Apply date range filters to content counts
        $query->withCount([
            'contents as contents_count' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            },
            'histories as updates_count' => function ($q) use ($startDate, $endDate) {
                $q->where('action', 'update')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
        ])
            ->with('role')
            ->orderBy('contents_count', 'desc');

        // Get all users (no pagination for PDF)
        $users = $query->get();

        // Get all users for the filter dropdown (also excluding admin/test users)
        $allUsers = User::whereNotIn('email', ['bogdadishahali@gmail.com', 'admin@bongosoftbd.com'])
            ->orderBy('name')
            ->get();

        // Load PDF view
        $html = view('admin.report.pdf', compact('users', 'allUsers', 'startDate', 'endDate', 'userId'))->render();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ]
            ],
            'default_font' => 'solaimanlipi',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        // Generate filename
        $filename = 'user-report-' . $startDate . '-to-' . $endDate;
        if ($userId) {
            $userName = $allUsers->firstWhere('id', $userId)->name ?? 'user';
            $filename .= '-' . str_replace(' ', '-', strtolower($userName));
        }
        $filename .= '.pdf';

        $mpdf->WriteHTML($html);
        return $mpdf->Output($filename, 'D');
    }

    /**
     * Download the filtered report as an Excel workbook.
     */
    public function exportExcel(Request $request, SimpleXlsxExporter $exporter)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', $startDate);
        $userId = $request->input('user_id');

        $query = User::query()
            ->whereNotIn('email', ['bogdadishahali@gmail.com', 'admin@bongosoftbd.com']);

        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->withCount([
            'contents as contents_count' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            },
            'histories as updates_count' => function ($q) use ($startDate, $endDate) {
                $q->where('action', 'update')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            },
        ])->with('role')->orderByDesc('contents_count')->get();

        $rows = [['Name', 'Email', 'Role', 'Created Content', 'Updated Content']];
        foreach ($users as $user) {
            $rows[] = [
                $user->name,
                $user->email,
                $user->role->name ?? 'N/A',
                $user->contents_count,
                $user->updates_count,
            ];
        }

        $path = $exporter->create($rows, 'User Report');
        $filename = "user-report-{$startDate}-to-{$endDate}.xlsx";

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export individual user's content list as PDF.
     */
    public function exportUserPdf(Request $request, User $user)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', $startDate);

        $contents = $user->contents()
            ->with(['category', 'writer', 'totalHits'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $html = view('admin.report.user-pdf', compact('user', 'contents', 'startDate', 'endDate'))->render();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ]
            ],
            'default_font' => 'solaimanlipi',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $filename = 'report-' . str_replace(' ', '-', strtolower($user->name)) . '-' . $startDate . '-to-' . $endDate . '.pdf';

        $mpdf->WriteHTML($html);
        return $mpdf->Output($filename, 'D');
    }
}
