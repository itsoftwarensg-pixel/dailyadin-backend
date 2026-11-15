<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RamadanTime;
use Illuminate\Http\Request;

class RamadanTimeController extends Controller
{
    public function index()
    {
        $ramadan = RamadanTime::where('name', 'ramadan_2026')->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => [
                'title'                 => $ramadan->title,
                'hijri_year'            => (int) $ramadan->hijri_year,
                'ramadan_start_date'    => date('Y-m-d', strtotime($ramadan->ramadan_start_date)),
                'name'                  => $ramadan->name,
                'footnote'              => $ramadan->footnote,
                'days' => collect($ramadan->days)
                    ->filter(fn($day) => (int) $day['status'] !== 2)
                    ->map(fn($day) => [
                        'day'       => (int) $day['day'],
                        'enDate'    => date('Y-m-d', strtotime($day['enDate'])),
                        'sehri_end' => $day['sehri_end'],
                        'iftar'     => $day['iftar'],
                        'fajr'      => $day['fajr'],
                        'duhr'      => $day['duhr'],
                        'asr'       => $day['asr'],
                        'maghrib'   => $day['maghrib'],
                        'isha'      => $day['isha'],
                        'status'    => $day['status'],
                    ])
                    ->values(),
                'division_offsets'        => collect($ramadan->division_offsets)->map(function ($division) {
                    return [
                        'division_en_name' => $division['division_en_name'],
                        'division_bn_name' => $division['division_bn_name'],
                        'time_offset_minute' => $division['time_offset_minute'],
                    ];
                })->values(),
            ]
        ]);
    }
}
