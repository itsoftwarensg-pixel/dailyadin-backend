<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\BdDivision;
use App\Models\BdDistrict;
use App\Models\BdUpazila;
use App\Models\BnCategory;
use App\Models\BnContent;
use App\Models\BnSubCategory;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BnContentResource;

class BnContentController extends Controller
{
    /**
     * Single Content API
     */
    public function getContent(Request $request, $id)
    {
        $includes = explode(',', $request->include ?? '');

        $content = BnContent::with(['category', 'writer', 'tags'])
            ->where('show_content', 1)
            ->findOrFail($id);

        // One atomic write prevents a read/write race and reduces database load
        // during traffic spikes on a popular article.
        rescue(fn () => DB::statement(
            'INSERT INTO bn_total_hits (bn_content_id, total_hits) VALUES (?, 1) '
            . 'ON DUPLICATE KEY UPDATE total_hits = total_hits + 1',
            [$content->id]
        ));

        return response()->json([
            'success' => true,
            'data' => new BnContentResource($content),
        ]);
    }

    /**
     * Unified Content API
     * Query Params: for (category, subcategory, division, district, upazila, writer), id, slug, limit, include
     */
    public function getContents(Request $request)
    {
        $includes = explode(',', $request->include ?? '');
        $for = $request->input('for');
        $id = $request->input('id');
        $slug = $request->input('slug');
        $limit = min(max((int) $request->input('limit', 10), 10), 100);

        $query = BnContent::with(['category', 'writer']) // Default eager loads
            ->when(in_array('tags', $includes), fn($q) => $q->with('tags'))
            ->when(in_array('district', $includes), fn($q) => $q->with('district'))
            ->when(in_array('upazila', $includes), fn($q) => $q->with('upazila'));

        // Filtering by "for"
        if ($for && ($id || $slug)) {
            // Resolve ID from slug if needed
            if (!$id && $slug) {
                switch ($for) {
                    case 'category':
                        $id = BnCategory::where('alias', $slug)->value('id');
                        break;
                    case 'subcategory':
                        $id = BnSubCategory::where('alias', $slug)->value('id');
                        break;
                    case 'division':
                        $id = BdDivision::where('alias', $slug)->value('id');
                        break;
                    case 'district':
                        $id = BdDistrict::where('alias', $slug)->value('id');
                        break;
                    case 'upazila':
                        $id = BdUpazila::where('alias', $slug)->value('id');
                        break;
                    case 'writer':
                        $id = Writer::where('alias', $slug)->value('id');
                        break;
                }
            }

            if ($id) {
                switch ($for) {
                    case 'category':
                        $query->byCategory($id);
                        break;
                    case 'subcategory':
                        $query->bySubCategory($id);
                        break;
                    case 'division':
                        $query->byDivision($id);
                        break;
                    case 'district':
                        $query->byDistrict($id);
                        break;
                    case 'upazila':
                        $query->byUpazila($id);
                        break;
                    case 'writer':
                        $query->byWriter($id);
                        break;
                    case 'tag':
                        $query->byTag($id);
                        break;
                }
            }
        }

        $contents = $query->where('show_content', 1)->latest()->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $contents->firstItem(),
                'per_page' => $contents->perPage(),
                'current_page' => $contents->currentPage(),
                'last_page' => $contents->lastPage(),
                'to' => $contents->lastItem(),
                'total' => $contents->total(),
                'data' => BnContentResource::collection($contents),
            ]
        ]);
    }

    /**
     * Archive Search API
     * Query Params: keyword, date_start, date_end, category_id, division_id, district_id, upazila_id
     */
    public function getArchive(Request $request)
    {
        $limit = min(max((int) $request->input('limit', 10), 10), 100);
        $keyword = $request->input('keyword');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        $query = BnContent::with('category', 'writer');

        // Apply filters using scopes
        if ($keyword)
            $query->byKeyword($keyword);
        if ($dateStart)
            $query->byDate($dateStart, $dateEnd);

        if ($request->category_id)
            $query->byCategory($request->category_id);
        if ($request->division_id)
            $query->byDivision($request->division_id);
        if ($request->district_id)
            $query->byDistrict($request->district_id);
        if ($request->upazila_id)
            $query->byUpazila($request->upazila_id);
        if ($request->writer_id)
            $query->byWriter($request->writer_id);

        $contents = $query->where('show_content', StatusEnum::ACTIVE->value)->latest()->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $contents->firstItem(),
                'per_page' => $contents->perPage(),
                'current_page' => $contents->currentPage(),
                'last_page' => $contents->lastPage(),
                'to' => $contents->lastItem(),
                'total' => $contents->total(),
                'data' => BnContentResource::collection($contents),
            ]
        ]);
    }

    /**
     * Content Search API
     * Query Params: heading
     */
    public function contentSearch(Request $request)
    {
        $heading = $request->input('heading');

        if (empty($heading)) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $limit = min(max((int) $request->input('limit', 10), 10), 100);

        $contents = BnContent::with('category', 'writer')
            ->where('heading', 'LIKE', "%{$heading}%")
            ->where('show_content', StatusEnum::ACTIVE->value)
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $contents->firstItem(),
                'per_page' => $contents->perPage(),
                'current_page' => $contents->currentPage(),
                'last_page' => $contents->lastPage(),
                'to' => $contents->lastItem(),
                'total' => $contents->total(),
                'data' => BnContentResource::collection($contents),
            ]
        ]);
    }
}
