<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WriterResource;
use App\Models\Writer;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    /**
     * Get all writers
     */
    public function getWriters(Request $request)
    {
        $includes = explode(',', $request->include ?? '');

        $writers = Writer::where('status', 1)
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->latest()->limit(10);
                }]);
            })
            ->paginate($request->limit ?? 20);

        return response()->json([
            'success' => true,
            'data' => [
                'from'         => $writers->firstItem(),
                'per_page'     => $writers->perPage(),
                'current_page' => $writers->currentPage(),
                'last_page'    => $writers->lastPage(),
                'to'           => $writers->lastItem(),
                'total'        => $writers->total(),
                'data'         => WriterResource::collection($writers),
            ]
        ]);
    }

    /**
     * Get single writer by ID
     */
    public function getWriter(Request $request, $id)
    {
        return $this->fetchWriter($request, 'id', $id);
    }

    /**
     * Get single writer by slug
     */
    public function getWriterBySlug(Request $request, $slug)
    {
        return $this->fetchWriter($request, 'alias', $slug);
    }

    /**
     * Common method to fetch writer
     */
    private function fetchWriter(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        $writer = Writer::where($field, $value)
            ->where('status', 1)
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->latest()->limit(10);
                }]);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new WriterResource($writer),
        ]);
    }
}
