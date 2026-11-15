<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RamadanTime;
use Illuminate\Http\Request;

class RamadanTimeController extends Controller
{
    public function index()
    {
        $ramadan = RamadanTime::where('name', 'ramadan_2026')->firstOrFail();
        return view('admin.ramadan.index', compact('ramadan'));
    }

    public function update(Request $request)
    {
//        dd($request->all());

        $ramadan = RamadanTime::where('name', 'ramadan_2026')->firstOrFail();

        $title              = $request->input('title');
        $hijri_year         = $request->input('hijri_year');
        $ramadan_start_date = $request->input('ramadan_start_date');
        $days               = $request->input('days');
        $footnote           = $request->input('footnote');
        $division_offsets   = $request->input('division_offsets');

        // Validation
        foreach ($days as $day) {
            if (!isset($day['sehri_end']) || !isset($day['iftar'])) {
                return back()->with('error', 'Invalid data!');
            }
        }

        $ramadan->update([
            'title'                 => $title,
            'hijri_year'            => $hijri_year,
            'ramadan_start_date'    => $ramadan_start_date,
            'days'                  => $days,
            'footnote'              => $footnote,
            'division_offsets'      => $division_offsets
        ]);

        return redirect()
            ->route('ramadan.index')
            ->with('message', 'Ramadan time updated successfully!');
    }
}
