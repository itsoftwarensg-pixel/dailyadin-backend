<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\WebstoryCategory;
use Illuminate\Http\Request;

class WebstoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = WebstoryCategory::latest()->paginate(15);
        return view('admin.webstory.category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.webstory.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'slug'              => 'nullable|unique:webstory_categories,slug',
            'featured_image'    => 'image',
        ]);

        $category = new WebstoryCategory();
        $category->name               = $request->name;
        $category->slug               = $request->slug;
        $category->description        = $request->description;
        $category->featured_image     = $request->featured_image;
        $category->meta_title         = $request->meta_title;
        $category->meta_keywords      = $request->meta_keywords;
        $category->meta_description   = $request->meta_description;

        if ($request->featured_image) {
            $category->featured_image = FileUploadHelper::uploadFile($request->featured_image, $category->featured_image);
        }

        $category->save();
        return redirect()->route('webstory-category.index')->with('message', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WebstoryCategory $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebstoryCategory $webstoryCategory)
    {
        return view('admin.webstory.category.edit', [
            'category' => $webstoryCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebstoryCategory $webstoryCategory)
    {
        $request->validate([
            'name'              => 'required',
            'slug'              => 'nullable|unique:webstory_categories,slug,' . $webstoryCategory->id,
            'featured_image'    => 'image',
            'status'            => 'required',
        ]);

        $webstoryCategory->name               = $request->name;
        $webstoryCategory->slug               = $request->slug;
        $webstoryCategory->description        = $request->description;
        $webstoryCategory->meta_title         = $request->meta_title;
        $webstoryCategory->meta_keywords      = $request->meta_keywords;
        $webstoryCategory->meta_description   = $request->meta_description;
        $webstoryCategory->status             = $request->status;

        if ($request->featured_image) {
            $webstoryCategory->featured_image = FileUploadHelper::uploadFile($request->featured_image, $webstoryCategory->featured_image);
        }

        $webstoryCategory->update();
        return redirect()->route('webstory-category.index')->with('message', 'successfully Category information updated!');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(WebstoryCategory $webstoryCategory)
    {
        // Mark as inactive
        $webstoryCategory->status = StatusEnum::INACTIVE->value;
        $webstoryCategory->save();

        // Soft delete
        $webstoryCategory->delete();

        return redirect()->route('webstory-category.index')->with('message', 'Webstory category has been deleted from this system!');
    }
}
