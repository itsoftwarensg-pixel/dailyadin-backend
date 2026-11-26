<?php

namespace App\Http\Controllers\API;

use App\Models\BdUpazila;
use App\Models\BdDistrict;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BnDistrictResource;
use App\Http\Resources\BnUpazilaResource;

class BnDistrictController extends Controller
{
    /**
     * All Districts
     */
    public function getDistricts(Request $request)
    {
        $includes = explode(',', $request->include ?? '');

        $districts = BdDistrict::with('division')->get();

        return response()->json([
            'success' => true,
            'data' => BnDistrictResource::collection($districts),
        ]);
    }

    /**
     * Single District
     */
    /**
     * Single District by ID
     */
    public function getDistrict(Request $request, $id)
    {
        return $this->fetchDistrict($request, 'id', $id);
    }

    /**
     * Single District by Slug
     */
    public function getDistrictBySlug(Request $request, $slug)
    {
        return $this->fetchDistrict($request, 'alias', $slug);
    }

    /**
     * Common method to fetch district
     */
    private function fetchDistrict(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        $district = BdDistrict::where($field, $value)
            ->with('division')
            ->when(in_array('upazilas', $includes), fn($q) => $q->with('upazilas'))
            ->when(in_array('contents', $includes), function ($query) {
                $query->with([
                    'contents' => function ($q) {
                    $q->where('show_content', 1)->latest()->limit(10);
                    },
                ]);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new BnDistrictResource($district),
        ]);
    }

    /**
     * All Upazillas By District ID
     */
    public function getUpazilas(Request $request, $id)
    {
        return $this->fetchUpazilasByDistrict($request, 'id', $id);
    }

    /**
     * All Upazillas By District Slug
     */
    public function getUpazilasBySlug(Request $request, $slug)
    {
        return $this->fetchUpazilasByDistrict($request, 'alias', $slug);
    }

    /**
     * Common method to fetch upazilas by district
     */
    private function fetchUpazilasByDistrict(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        // Resolve district ID if field is NOT id
        $districtId = ($field === 'id') ? $value : BdDistrict::where($field, $value)->value('id');

        if (!$districtId) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'District not found'
            ], 404);
        }

        $upazillas = BdUpazila::where('district_id', $districtId)
            ->with('district.division')
            ->when(in_array('contents', $includes), function ($query) {
                $query->with([
                    'contents' => function ($q) {
                        $q->where('show_content', 1)->latest()->limit(10);
                    },
                ]);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => BnUpazilaResource::collection($upazillas),
        ]);
    }
}
