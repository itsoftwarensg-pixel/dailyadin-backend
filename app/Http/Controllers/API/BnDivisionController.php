<?php

namespace App\Http\Controllers\API;

use App\Models\BdDistrict;
use App\Models\BdDivision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BnDistrictResource;
use App\Http\Resources\BnDivisionResource;

class BnDivisionController extends Controller
{
    /**
     * All Divisions
     */
    public function getDivisions(Request $request)
    {
        $includes = explode(',', $request->include ?? '');

        $divisions = BdDivision::when(in_array('contents', $includes), function ($query) {
            $query->with([
                'contents' => function ($q) {
                    $q->latest()->limit(10);
                },
            ]);
        })->get();

        return response()->json([
            'success' => true,
            'data' => BnDivisionResource::collection($divisions),
        ]);
    }

    /**
     * Single Division by ID
     */
    public function getDivision(Request $request, $id)
    {
        return $this->fetchDivision($request, 'id', $id);
    }

    /**
     * Single Division by Slug
     */
    public function getDivisionBySlug(Request $request, $slug)
    {
        return $this->fetchDivision($request, 'alias', $slug);
    }

    /**
     * Common method to fetch division
     */
    private function fetchDivision(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        $division = BdDivision::where($field, $value)
            ->when(in_array('districts', $includes), fn($q) => $q->with('districts'))
            ->when(in_array('contents', $includes), function ($query) {
                $query->with([
                    'contents' => function ($q) {
                        $q->latest()->limit(10);
                    },
                ]);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new BnDivisionResource($division),
        ]);
    }

    /**
     * All Districts By Division ID
     */
    public function getDistricts(Request $request, $id)
    {
        return $this->fetchDistrictsByDivision($request, 'id', $id);
    }

    /**
     * All Districts By Division Slug
     */
    public function getDistrictsBySlug(Request $request, $slug)
    {
        return $this->fetchDistrictsByDivision($request, 'alias', $slug);
    }

    /**
     * Common method to fetch districts by division
     */
    private function fetchDistrictsByDivision(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        // Resolve division ID if field is NOT id
        $divisionId = ($field === 'id') ? $value : BdDivision::where($field, $value)->value('id');

        if (!$divisionId) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Division not found'
            ], 404);
        }

        $districts = BdDistrict::where('division_id', $divisionId)
            ->with('division')
            ->when(in_array('upazilas', $includes), fn($q) => $q->with('upazilas'))
            ->when(in_array('contents', $includes), function ($query) {
                $query->with([
                    'contents' => function ($q) {
                        $q->latest()->limit(10);
                    },
                ]);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => BnDistrictResource::collection($districts),
        ]);
    }
}
