<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function nationalElection2026() {
        $dataset = Election::where('name', 'national_election_2026')
            ->where('status', 1)
            ->firstOrFail();

        $data = json_decode($dataset->data, true);

        return view('admin.election.election-2026', [
            'dataset' => $dataset,
            'data' => $data
        ]);
    }

    public function nationalElection2026Update(Request $request)
    {
        $dataset = Election::where('name','national_election_2026')->firstOrFail();

        $dataset->data = $request->data;
        $dataset->save();

        return redirect()->route('nationalElection2026')->with('message', 'Election Data Updated!');
    }
}
