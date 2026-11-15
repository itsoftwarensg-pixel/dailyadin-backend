<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoAlbumResource;
use App\Models\PhotoAlbum;
use App\Models\PhotoCategory;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Get list of photo albums
     */
    public function index(Request $request)
    {
        $limit = min(max((int) $request->input('limit', 20), 1), 20);

        $albums = PhotoAlbum::with('category')
            ->where('status', 1)
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data'    => [
                'from'          => $albums->firstItem(),
                'per_page'      => $albums->perPage(),
                'current_page'  => $albums->currentPage(),
                'last_page'     => $albums->lastPage(),
                'to'            => $albums->lastItem(),
                'total'         => $albums->total(),
                'data'          => PhotoAlbumResource::collection($albums),
            ]
        ]);
    }

    /**
     * Get album details
     */
    public function details($id)
    {
        $album = PhotoAlbum::with(['category', 'contents'])
            ->where('id', $id)
            ->where('status', 1) // Active
            ->firstOrFail();

        return new PhotoAlbumResource($album);
    }

    /**
     * Get albums by category
     */
    public function categoryAlbums($id, Request $request)
    {
        $limit = min(max((int) $request->input('limit', 20), 1), 20);

        // Assuming $id can be ID or Slug? User said 'category wise'.
        // Let's support ID for now as PhotoCategory doesn't seem to have slug in standard schema but might have.
        // I will assume ID. If they need slug, I'll need to check PhotoCategory columns more closely (it was empty in my view_file).
        // Let's assume passed ID for now.

        $albums = PhotoAlbum::with('category')
            ->where('photo_category_id', $id)
            ->where('status', 1)
            ->latest()
            ->paginate($limit);

        return PhotoAlbumResource::collection($albums);
    }
}
