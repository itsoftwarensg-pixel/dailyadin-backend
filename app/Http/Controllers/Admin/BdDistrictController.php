<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BdDistrict;
use App\Models\BdDivision;
use Illuminate\Http\Request;

class BdDistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = BdDivision::with('districts')->get();
        return view('admin.district.index', [
            'divisions' => $divisions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(403, 'Creating new district is not allowed.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(403, 'Creating new district is not allowed.');
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
    public function edit(BdDistrict $district)
    {
        return view('admin.district.edit', [
            'district' => $district
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BdDistrict $district)
    {
        $request->validate(
            [
                'photo' => 'image',
            ]
        );

        $district->name               = $request->name;
        $district->name_bn            = $request->name_bn;
        $district->alias              = $request->alias;
        $district->meta_title         = $request->meta_title;
        $district->meta_keywords      = $request->meta_keywords;
        $district->meta_description   = $request->meta_description;
        $district->description        = $request->description;

        if ($request->photo) {
            $district->photo = FileUploadHelper::uploadFile($request->photo, $district->photo);
        }

        $district->update();
        return redirect()->route('district.index')->with('message', 'Successfully district information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(403, 'Deleting district is not allowed.');
    }
}
