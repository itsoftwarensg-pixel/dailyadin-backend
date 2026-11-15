<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BnSpecialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class BnSpecialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialCategories = BnSpecialCategory::all();
        return view('admin.special-category.index', [
            'specialCategories' => $specialCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.special-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'              => 'required',
                'image_icon'        => 'image',
                'image_menu'        => 'image',
                'image_cover_home'  => 'image',
                'image_cover_inner' => 'image',
            ]
        );

        $specialCategory = new BnSpecialCategory();
        $specialCategory->name               = $request->name;
        $specialCategory->alias              = $request->alias;
        $specialCategory->end_note           = $request->end_note;
        $specialCategory->description        = $request->description;
        $specialCategory->sorting            = $request->sorting;
        $specialCategory->status             = $request->status;

        /* image_icon */
        if ($request->image_icon) {
            $specialCategory->image_icon = FileUploadHelper::uploadFile($request->image_icon, $specialCategory->image_icon);
        }

        /* image_menu */
        if ($request->image_menu) {
            $specialCategory->image_menu = FileUploadHelper::uploadFile($request->image_menu, $specialCategory->image_menu);
        }

        /* image_cover_home */
        if ($request->image_cover_home) {
            $specialCategory->image_cover_home = FileUploadHelper::uploadFile($request->image_cover_home, $specialCategory->image_cover_home);
        }

        /* image_cover_inner */
        if ($request->image_cover_inner) {
            $specialCategory->image_cover_inner = FileUploadHelper::uploadFile($request->image_cover_inner, $specialCategory->image_cover_inner);
        }

        $specialCategory->save();
        return redirect()->route('special-category.index')->with('message', 'New special category added!');
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
    public function edit(BnSpecialCategory $specialCategory)
    {
        return view('admin.special-category.edit', [
            'specialCategory' => $specialCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BnSpecialCategory $specialCategory)
    {
        $request->validate(
            [
                'name'              => 'required',
                'image_icon'        => 'image',
                'image_menu'        => 'image',
                'image_cover_home'  => 'image',
                'image_cover_inner' => 'image',
            ]
        );

        $specialCategory->name               = $request->name;
        $specialCategory->alias              = $request->alias;
        $specialCategory->end_note           = $request->end_note;
        $specialCategory->description        = $request->description;
        $specialCategory->sorting            = $request->sorting;
        $specialCategory->status             = $request->status;

        /* image_icon */
        if ($request->image_icon) {
            $specialCategory->image_icon = FileUploadHelper::uploadFile($request->image_icon, $specialCategory->image_icon);
        }

        /* image_menu */
        if ($request->image_menu) {
            $specialCategory->image_menu = FileUploadHelper::uploadFile($request->image_menu, $specialCategory->image_menu);
        }

        /* image_cover_home */
        if ($request->image_cover_home) {
            $specialCategory->image_cover_home = FileUploadHelper::uploadFile($request->image_cover_home, $specialCategory->image_cover_home);
        }

        /* image_cover_inner */
        if ($request->image_cover_inner) {
            $specialCategory->image_cover_inner = FileUploadHelper::uploadFile($request->image_cover_inner, $specialCategory->image_cover_inner);
        }

        $specialCategory->update();
        return redirect()->route('special-category.index')->with('message', 'Successfully special category information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BnSpecialCategory $specialCategory)
    {
        if (auth()->user()->role_id == 1) {
            // Mark as inactive
            $specialCategory->status = StatusEnum::INACTIVE->value;
            $specialCategory->save();

            // Soft delete
            $specialCategory->delete();

            return redirect()->route('special-category.index')
                ->with('message', 'Special Category has been deleted from this system!');
        }

        return redirect()->route('special-category.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }
}
