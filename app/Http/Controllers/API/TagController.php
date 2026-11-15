<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    /**
     * Get all tags
     */
    public function getTags(Request $request)
    {
        $includes = explode(',', $request->include ?? '');
        
        $tags = Tag::when($request->search, fn($q) => $q->search($request->search))
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->latest()->limit(10);
                }]);
            })
            ->paginate($request->limit ?? 20);

        return response()->json([
            'success' => true,
            'data' => [
                'from'         => $tags->firstItem(),
                'per_page'     => $tags->perPage(),
                'current_page' => $tags->currentPage(),
                'last_page'    => $tags->lastPage(),
                'to'           => $tags->lastItem(),
                'total'        => $tags->total(),
                'data'         => TagResource::collection($tags),
            ]
        ]);
    }

    /**
     * Get single tag by ID
     */
    public function getTag(Request $request, $id)
    {
        return $this->fetchTag($request, 'id', $id);
    }

    /**
     * Get single tag by slug
     */
    public function getTagBySlug(Request $request, $slug)
    {
        return $this->fetchTag($request, 'alias', $slug);
    }

    /**
     * Common method to fetch tag
     */
    private function fetchTag(Request $request, $field, $value)
    {
        $includes = explode(',', $request->include ?? '');

        $tag = Tag::where($field, $value)
            ->when(in_array('contents', $includes), function ($query) {
                $query->with(['contents' => function ($q) {
                    $q->latest()->limit(10);
                }]);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new TagResource($tag),
        ]);
    }
}
