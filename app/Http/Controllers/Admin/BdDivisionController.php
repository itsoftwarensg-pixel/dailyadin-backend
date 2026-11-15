<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BdDivision;
use Illuminate\Http\Request;

class BdDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = BdDivision::whereNot('name', 'NONE')->get();
        return view('admin.division.index', [
            'divisions' => $divisions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(403, 'Creating new division is not allowed.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(403, 'Creating new division is not allowed.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BdDivision $division)
    {
        return view('admin.division.edit', [
            'division' => $division
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BdDivision $division)
    {
        $request->validate(
            [
                'photo' => 'image',
            ]
        );

        $division->meta_title         = $request->meta_title;
        $division->meta_keywords      = $request->meta_keywords;
        $division->meta_description   = $request->meta_description;
        $division->description        = $request->description;

        if ($request->photo) {
            $division->photo = FileUploadHelper::uploadFile($request->photo, $division->photo);
        }

        $division->update();
        return redirect()->route('division.index')->with('message', 'Successfully division information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(403, 'Deleting division is not allowed.');
    }
}
