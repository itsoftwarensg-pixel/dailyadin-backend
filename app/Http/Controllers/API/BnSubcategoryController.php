<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BnSubcategoryResource;
use App\Models\BnSubCategory;
use Illuminate\Http\Request;

class BnSubcategoryController extends Controller
{
    /**
     * Single Subcategory by ID
     */
    public function getSubcategory(Request $request, $id)
    {
        return $this->fetchSubcategory($request, 'id', $id);
    }

    /**
     * Single subcategory by slug
     */
    public function getSubCategoryBySlug(Request $request, $slug, $subSlug = null)
    {
        // If two parameters are passed, the first is category slug, second is subcategory slug
        if ($subSlug) {
            return $this->fetchSubcategory($request, 'alias', $subSlug, $slug);
        }

        // Fallback: only subcategory slug
        return $this->fetchSubcategory($request, 'alias', $slug);
    }

    /**
     * Common method to fetch subcategory
     */
    private function fetchSubcategory(Request $request, $field, $value, $categorySlug = null)
    {
        $includes = explode(',', $request->include ?? '');

        $query = BnSubcategory::where($field, $value)->with('category');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('alias', $categorySlug);
            });
        }

        $subcategory = $query->when(in_array('contents', $includes), function ($query) {
            $query->with([
                'contents' => function ($q) {
                    $q->with('category')->latest()->limit(10);
                },
            ]);
        })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new BnSubcategoryResource($subcategory),
        ]);
    }
}
