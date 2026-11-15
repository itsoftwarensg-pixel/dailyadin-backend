<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BdDistrict;
use App\Models\BdDivision;
use App\Models\BdUpazila;
use Illuminate\Http\Request;

class BdUpazilaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BdDistrict::with(['division', 'upazilas' => function ($q) use ($request) {
            if ($request->filled('upazila')) {
                $q->where('name', 'like', '%'.$request->upazila.'%')
                    ->orWhere('name_bn', 'like', '%'.$request->upazila.'%')
                    ->orWhere('alias', 'like', '%'.$request->upazila.'%');
            }
        }]);

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('district_id')) {
            $query->where('id', $request->district_id);
        }

        $districts = $query->get();

        // Remove districts with no matching upazilas
        $districts = $districts->filter(fn($district) => $district->upazilas->isNotEmpty());

        $divisions = BdDivision::all();
        $allDistricts = collect();
        if ($request->filled('division_id')) {
            $allDistricts = BdDistrict::where('division_id', $request->division_id)->get();
        }

        return view('admin.upazila.index', compact('districts', 'divisions', 'allDistricts'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = BdDivision::all();
        $districts = BdDistrict::all();

        return view('admin.upazila.create', compact('divisions', 'districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer|exists:bd_districts,id',
            'name'        => 'required|string|max:255',
            'name_bn'     => 'nullable|string|max:255',
            'photo'       => 'nullable|image',
        ]);

        $upazila = new BdUpazila();
        $upazila->district_id        = $request->district_id;
        $upazila->name               = $request->name;
        $upazila->name_bn            = $request->name_bn;
        $upazila->alias              = $request->alias;

        $upazila->meta_title         = $request->meta_title;
        $upazila->meta_keywords      = $request->meta_keywords;
        $upazila->meta_description   = $request->meta_description;
        $upazila->description        = $request->description;

        if ($request->photo) {
            $upazila->photo = FileUploadHelper::uploadFile($request->photo);
        }

        $upazila->save();
        return redirect()->route('upazila.index')->with('message', 'Successfully upazila created!');
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
    public function edit(BdUpazila $upazila)
    {
        return view('admin.upazila.edit', [
            'upazila' => $upazila
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BdUpazila $upazila)
    {
        $request->validate(
            [
                'photo' => 'image',
            ]
        );

        $upazila->name               = $request->name;
        $upazila->name_bn            = $request->name_bn;
        $upazila->alias              = $request->alias;

        $upazila->meta_title         = $request->meta_title;
        $upazila->meta_keywords      = $request->meta_keywords;
        $upazila->meta_description   = $request->meta_description;
        $upazila->description        = $request->description;

        if ($request->photo) {
            $upazila->photo = FileUploadHelper::uploadFile($request->photo, $upazila->photo);
        }

        $upazila->update();
        return redirect()->route('upazila.index')->with('message', 'Successfully upazila information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(403, 'Deleting upazila is not allowed.');
    }
}
