<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BnManagePositionEnum;
use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BnCategory;
use App\Models\BnManagePosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

class BnCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $categories = BnCategory::query();

        if ($search) {
            $categories = $categories
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('alias', 'like', '%' . $search . '%');
        }


        $categories = $categories
            ->orderBy('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.category.index', [
            'categories' => $categories
        ]);
    }

    /*public function index(Request $request)
    {
        $search = $request->search;
        $page = $request->get('page', 1);
        $perPage = 30; // updated pagination

        // Get Redis prefix from config
        $prefix = config('cache.prefix'); // e.g., 'news_portal:'

        // Build cache key
        $redisKey = "{$prefix}categories:{$search}:page:{$page}:perpage:{$perPage}";

        // Attempt to get cached data
        $cached = Redis::get($redisKey);
        if ($cached) {
            $categories = unserialize($cached);
        } else {
            // Build query
            $categoriesQuery = BnCategory::query();
            if ($search) {
                $categoriesQuery->where('name', 'like', '%'.$search.'%')
                    ->orWhere('alias', 'like', '%'.$search.'%');
            }

            $categories = $categoriesQuery->latest('id')
                ->paginate($perPage)
                ->withQueryString();

            // Store in Redis forever
            Redis::set($redisKey, serialize($categories));
        }


        return view('admin.category.index', [
            'categories' => $categories
        ]);
    }*/

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'  => 'required',
                'photo' => 'image',
            ]
        );

        $category = new BnCategory();
        $category->name               = $request->name;
        $category->alias              = $request->alias;
        $category->description        = $request->description;
        $category->meta_title         = $request->meta_title;
        $category->meta_keywords      = $request->meta_keywords;
        $category->meta_description   = $request->meta_description;
        $category->sorting            = $request->sorting;
        $category->status             = $request->status;

        if ($request->photo) {
            $category->photo = FileUploadHelper::uploadFile($request->photo, $category->photo);
        }

        $category->save();

        // Insert default positions for the newly created category
        BnManagePosition::insert([
            [
                'position_type'    => BnManagePositionEnum::CATEGORY_HOME_POSITION->value,
                'position_type_id' => $category->id,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'position_type'    => BnManagePositionEnum::CATEGORY_INNER_POSITION->value,
                'position_type_id' => $category->id,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);

        return redirect()->route('category.index')->with('message', 'New category added!');
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
    public function edit(BnCategory $category)
    {
        return view('admin.category.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BnCategory $category)
    {
        $request->validate(
            [
                'name'  => 'required',
                'photo' => 'image',
            ]
        );

        $category->name               = $request->name;
        $category->alias              = $request->alias;
        $category->description        = $request->description;
        $category->meta_title         = $request->meta_title;
        $category->meta_keywords      = $request->meta_keywords;
        $category->meta_description   = $request->meta_description;
        $category->sorting            = $request->sorting;
        $category->status             = $request->status;

        if ($request->photo) {
            $category->photo = FileUploadHelper::uploadFile($request->photo, $category->photo);
        }

        $category->update();

        // Cache is automatically flushed via model events

        return redirect()
            ->route('category.index', ['page' => $request->page])
            ->with('message', 'Successfully category information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BnCategory $category)
    {
        if (auth()->user()->role_id == 1) {
            // Mark as inactive
            $category->status = StatusEnum::INACTIVE->value;
            $category->save();

            // Soft delete
            $category->delete();

            // Cache is automatically flushed via model events

            return redirect()->route('category.index')
                ->with('message', 'Category has been deleted from this system!');
        }

        return redirect()->route('category.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }
}
