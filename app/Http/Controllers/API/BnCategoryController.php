<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\BnCategoryResource;
use App\Http\Resources\BnSubcategoryResource;
use App\Models\BnCategory;
use App\Models\BnContent;
use App\Models\BnManagePosition;
use App\Models\BnSubCategory;
use Illuminate\Http\Request;

class BnCategoryController extends Controller
{
    /**
     * All subcategories for a category by ID
     */
    public function getSubcategories(Request $request, $id)
    {
        return $this->fetchSubcategoriesByCategory($request, 'id', $id);
    }

    /**
     * All subcategories for a category by Slug
     */
    public function getSubcategoriesBySlug(Request $request, $slug)
    {
        return $this->fetchSubcategoriesByCategory($request, 'alias', $slug);
    }

    /**
     * Common method to fetch subcategories by category
     */
    private function fetchSubcategoriesByCategory(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        // Resolve category ID
        $categoryId = ($field === 'id') ? $value : BnCategory::where($field, $value)->value('id');

        if (!$categoryId) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Category not found'
            ], 404);
        }

        $subcategories = BnSubCategory::where('category_id', $categoryId)
            ->with('category')
            ->orderBy('sorting', 'asc')
            ->when(in_array('contents', $includes), function ($query) {
                $query->with([
                    'contents' => function ($q) {
                        $q->with('category')->latest()->limit(10);
                    },
                ]);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => BnSubcategoryResource::collection($subcategories),
        ]);
    }

    /**
     * All categories
     */
    public function getCategories(Request $request)
    {
        // Get categories
        $includes = explode(',', $request->include ?? '');

        $categories = BnCategory::orderBy('sorting', 'asc')
            ->when(in_array('subcategories', $includes), fn($q) => $q->with('subcategories'))
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->with('category');
                    $q->latest()->limit(10);
                }]);
            })
            ->where('status', StatusEnum::ACTIVE->value)
            ->get();

        if (in_array('position_contents', $includes)) {
            $categories->each(function ($category) {
                $this->loadPositionContents($category);
            });
        }

        return response()->json([
            'success' => true,
            'data'    => BnCategoryResource::collection($categories)
        ]);
    }

    /**
     * Single category by ID
     */
    public function getCategory(Request $request, $id)
    {
        return $this->fetchCategory($request, 'id', $id);
    }

    /**
     * Single category by slug
     */
    public function getCategoryBySlug(Request $request, $slug)
    {
        return $this->fetchCategory($request, 'alias', $slug);
    }

    /**
     * Common method to fetch category
     */
    private function fetchCategory(Request $request, $field, $value)
    {
        // Get includes
        $includes = explode(',', $request->include ?? '');

        // Get category
        $category = BnCategory::where($field, $value)
            ->when(in_array('subcategories', $includes), fn($q) => $q->with('subcategories'))
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->with('category');
                    $q->latest()->limit(10);
                }]);
            })->firstOrFail();

        // Fetch position contents if requested
        if (in_array('position_contents', $includes)) {
            $this->loadPositionContents($category);
        }

        return response()->json([
            'success' => true,
            'data'    => new BnCategoryResource($category)
        ]);
    }

    /**
     * Load position contents for a category
     */
    private function loadPositionContents($category)
    {
        $position = BnManagePosition::where('position_type', 'category_inner')
            ->where('position_type_id', $category->id)
            ->first();

        if ($position && $position->ids) {
            $ids = explode(',', $position->ids);
            $limit = min($position->max_content ?? 5, 5);
            $idsToFetch = array_slice($ids, 0, $limit);

            if (!empty($idsToFetch)) {
                $category->position_contents = BnContent::with('category')
                    ->whereIn('id', $idsToFetch)
                    ->orderByRaw("FIELD(id, " . implode(',', $idsToFetch) . ")")
                    ->get();
            }
        }
    }
}
