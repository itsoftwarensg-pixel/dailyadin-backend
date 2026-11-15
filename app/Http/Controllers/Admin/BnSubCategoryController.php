<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BnCategory;
use App\Models\BnSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class BnSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $subCategories = BnSubCategory::query()->with('category');

        if ($search) {
            $subCategories = $subCategories
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('alias', 'like', '%' . $search . '%');
        }


        $subCategories = $subCategories
            ->orderBy('id',)
            ->paginate(30)
            ->withQueryString();

        return view('admin.sub-category.index', [
            'subCategories' => $subCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BnCategory::where('status', StatusEnum::ACTIVE->value)->get();
        return view('admin.sub-category.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'category_id'   => 'required',
                'name'          => 'required',
                'photo'         => 'image',
            ]
        );

        $subCategory = new BnSubCategory();
        $subCategory->category_id        = $request->category_id;
        $subCategory->name               = $request->name;
        $subCategory->alias              = $request->alias;
        $subCategory->description        = $request->description;
        $subCategory->meta_title         = $request->meta_title;
        $subCategory->meta_keywords      = $request->meta_keywords;
        $subCategory->meta_description   = $request->meta_description;
        $subCategory->sorting            = $request->sorting;
        $subCategory->status             = $request->status;

        if ($request->photo) {
            $subCategory->photo = FileUploadHelper::uploadFile($request->photo, $subCategory->photo);
        }

        $subCategory->save();
        return redirect()->route('sub-category.index')->with('message', 'Successfully sub-category information updated!');
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
    public function edit(BnSubCategory $subCategory)
    {
        $categories = BnCategory::where('status', StatusEnum::ACTIVE->value)->get();
        return view('admin.sub-category.edit', [
            'categories' => $categories,
            'subCategory' => $subCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BnSubCategory $subCategory)
    {
        $request->validate(
            [
                'category_id'   => 'required',
                'name'          => 'required',
                'photo'         => 'image',
            ]
        );

        $subCategory->category_id        = $request->category_id;
        $subCategory->name               = $request->name;
        $subCategory->alias              = $request->alias;
        $subCategory->description        = $request->description;
        $subCategory->meta_title         = $request->meta_title;
        $subCategory->meta_keywords      = $request->meta_keywords;
        $subCategory->meta_description   = $request->meta_description;
        $subCategory->sorting            = $request->sorting;
        $subCategory->status             = $request->status;

        if ($request->photo) {
            $subCategory->photo = FileUploadHelper::uploadFile($request->photo, $subCategory->photo);
        }

        $subCategory->update();

        return redirect()
            ->route('sub-category.index', ['page' => $request->page])
            ->with('message', 'Successfully sub-category information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BnSubCategory $subCategory)
    {
        if (auth()->user()->role_id == 1) {
            // Mark as inactive
            $subCategory->status = StatusEnum::INACTIVE->value;
            $subCategory->save();

            // Soft delete
            $subCategory->delete();

            return redirect()->route('sub-category.index')
                ->with('message', 'Sub Category has been deleted from this system!');
        }

        return redirect()->route('sub-category.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }
}
