<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Get list of videos
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 20;

        $videos = Video::where('status', 1) // Active
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data'    => [
                'from'          => $videos->firstItem(),
                'per_page'      => $videos->perPage(),
                'current_page'  => $videos->currentPage(),
                'last_page'     => $videos->lastPage(),
                'to'            => $videos->lastItem(),
                'total'         => $videos->total(),
                'data'          => VideoResource::collection($videos),
            ]
        ]);
    }

    /**
     * Get video details
     */
    public function details($id)
    {
        $video = Video::where('id', $id)->where('status', 1)->firstOrFail();

        return new VideoResource($video);
    }
}
