<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BnUpazilaResource;
use App\Models\BdUpazila;
use Illuminate\Http\Request;

class BnUpazilaController extends Controller
{
    /**
     * Single Upazilla
     */
    /**
     * Single Upazilla by ID
     */
    public function getUpazila(Request $request, $id)
    {
        return $this->fetchUpazila($request, 'id', $id);
    }

    /**
     * Single Upazilla by Slug
     */
    public function getUpazilaBySlug(Request $request, $slug)
    {
        return $this->fetchUpazila($request, 'alias', $slug);
    }

    /**
     * Single Upazilla by District Slug and Upazila Slug
     */
    public function getUpazilaByDistrictAndSlug(Request $request, $districtSlug, $upazilaSlug)
    {
        $includes = explode(',', $request->include ?? '');

        $upazila = BdUpazila::where('alias', $upazilaSlug)
            ->with('district.division')
            ->whereHas('district', function ($q) use ($districtSlug) {
                $q->where('alias', $districtSlug);
            })
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->where('show_content', 1)->latest()->limit(10);
                }]);
            })
            ->firstOrFail();

        return response()->json([
            'success'   => true,
            'data'      => new BnUpazilaResource($upazila),
        ]);
    }

    /**
     * Common method to fetch upazila
     */
    private function fetchUpazila(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        $upazila = BdUpazila::where($field, $value)
            ->with('district.division')
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->where('show_content', 1)->latest()->limit(10);
                }]);
            })
            ->firstOrFail();

        return response()->json([
            'success'   => true,
            'data'      => new BnUpazilaResource($upazila),
        ]);
    }
}
