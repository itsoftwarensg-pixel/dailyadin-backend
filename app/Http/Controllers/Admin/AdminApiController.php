<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BdDistrict;
use App\Models\BdUpazila;
use App\Models\BnCategory;
use App\Models\BnSubCategory;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminApiController extends Controller
{
    /** API: Get subcategories by category
     * @params int $category_id
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getSubcategoriesByCategory(Request $request): JsonResponse {
        // Find category
        $category = BnCategory::query()->where('id', $request->category_id)->first();

        if (!$category) {
            return response()->json([]);
        }

        // Get active subcategories
        $subCategories = BnSubCategory::query()
            ->where('category_id', $category->id)
            ->where('status', 1) // optional, if you only want active
            ->select('id', 'name')
            ->get();

        return response()->json($subCategories);
    }

    /** API: Get upazilas by district
     * @params int $district_id
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getUpazilaByDistrict(Request $request): JsonResponse {
        // Find district
        $district = BdDistrict::query()->where('id', $request->district_id)->first();

        if (!$district) {
            return response()->json([]);
        }

        // Get active upazilas
        $upazilas = BdUpazila::query()
            ->where('district_id', $district->id)
            ->select('id', 'name', 'name_bn')
            ->get();

        return response()->json($upazilas);
    }

    /** API: Tag search
     * @params string $q
     * @param  Request  $request
     * @return JsonResponse
     */
    public function tagSearch(Request $request): JsonResponse {
        $term = $request->get('q'); // TokenInput usually sends `q` as query parameter

        $tags = Tag::query()
            ->when($term, fn($q) => $q->where('name', 'like', "%{$term}%"))
            ->where('status', StatusEnum::ACTIVE->value)
            ->orderBy('name')
            ->get(['id', 'name']); // only select required columns

        // Return JSON in format [{id:..., name:...}, ...]
        return response()->json($tags);
    }

    public function storeTag(StoreTagRequest $request)
    {
        $request->validate(
            [
                'name'  => 'required|unique:tags,name',
                'image' => 'image',
            ]
        );

        $tag = new Tag();
        $tag->name              = $request->name;
        $tag->alias             = $request->alias;
        $tag->description       = $request->description;
        $tag->meta_title        = $request->meta_title;
        $tag->meta_keywords     = $request->meta_keywords;
        $tag->meta_description  = $request->meta_description;
        $tag->sorting           = $request->sorting;
        $tag->status            = $request->status;

//        if ($request->image) {
//            $tag->image = FileUploadHelper::uploadFile($request->image);
//        }

//        $tag->save();

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'tag' => $tag
        ]);
    }
}
