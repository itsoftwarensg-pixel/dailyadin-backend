<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\PhotoCategory;
use Illuminate\Http\Request;

class PhotoCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = PhotoCategory::latest()->paginate(15);
        return view('admin.photo.category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.photo.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => 'nullable|unique:photo_categories,slug',
            'image' => 'image',
        ]);

        $category = new PhotoCategory();
        $category->name               = $request->name;
        $category->slug               = $request->slug;
        $category->description        = $request->description;
        $category->image              = $request->image;

        if ($request->image) {
            $category->image = FileUploadHelper::uploadFile($request->image, $category->image);
        }

        $category->save();
        return redirect()->route('photo-category.index')->with('message', 'Category created successfully.');
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
    public function edit(PhotoCategory $photoCategory)
    {
        return view('admin.photo.category.edit', [
            'category' => $photoCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PhotoCategory $photoCategory)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => 'nullable|unique:photo_categories,slug,' . $photoCategory->id,
            'image' => 'image',
        ]);

        $photoCategory->name               = $request->name;
        $photoCategory->slug               = $request->slug;
        $photoCategory->description        = $request->description;
        $photoCategory->image              = $request->image;
        $photoCategory->status             = $request->status;

        if ($request->image) {
            $photoCategory->image = FileUploadHelper::uploadFile($request->image, $photoCategory->image);
        }

        $photoCategory->update();
        return redirect()->route('photo-category.index')->with('message', 'successfully Category information updated!');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(PhotoCategory $photoCategory)
    {
        // Mark as inactive
        $photoCategory->status = StatusEnum::INACTIVE->value;
        $photoCategory->save();

        // Soft delete
        $photoCategory->delete();

        return redirect()->route('photo-category.index')->with('message', 'Photo category has been deleted from this system!');
    }
}
