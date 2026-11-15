<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PhotoAlbumRequest;
use App\Models\Photo;
use App\Models\PhotoAlbum;
use App\Models\PhotoCategory;
use Illuminate\Support\Facades\DB;

class PhotoAlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photoAlbums = PhotoAlbum::with('category')->latest('id')->paginate(15)->withQueryString();
        return view('admin.photo.album.index', [
            'photoAlbums' => $photoAlbums,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $photoCategories = PhotoCategory::where('status', StatusEnum::ACTIVE->value)->get();
        return view('admin.photo.album.create', [
            'photoCategories' => $photoCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PhotoAlbumRequest $request)
    {
        DB::beginTransaction(); // Start database transaction for temporarily storing data.
        try {
            // 1. Main Photo Album - Featured
            $photoAlbum = new PhotoAlbum();
            $photoAlbum->photo_category_id  = $request->photo_category_id;
            $photoAlbum->name               = $request->name;
            $photoAlbum->photographer_name  = $request->photographer_name;
            $photoAlbum->description        = $request->description;

            if ($request->image) {
                $photoAlbum->image = FileUploadHelper::uploadFile($request->image, $photoAlbum->image);
            }

            $photoAlbum->save();

            // 2. Album Photos - Other
            if ($request->has('contents')) {
                foreach ($request->contents as $content) {
                    $item = new Photo();
                    $item->photo_album_id   = $photoAlbum->id;
                    $item->caption          = $content['caption'] ?? null;
                    $item->photographer     = $content['photographer'] ?? null;
                    $item->show_at_home     = $content['show_at_home'] ?? null;
                    $item->photo_of_the_day = $content['photo_of_the_day'] ?? null;

                    if (isset($content['image'])) {
                        $item->image = FileUploadHelper::uploadFile($content['image'], $item->image);
                    }

                    $item->save();
                }
            }

            DB::commit(); // Final database execution
            return redirect()->route('photo-album.index')->with('message', 'Photo album created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhotoAlbum $photoAlbum)
    {
        // Eager load contents
        $photoAlbum->load('contents');

        $photoCategories = PhotoCategory::where('status', StatusEnum::ACTIVE->value)->get();
        return view('admin.photo.album.edit', [
            'photoCategories' => $photoCategories,
            'photoAlbum' => $photoAlbum,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PhotoAlbumRequest $request, PhotoAlbum $photoAlbum)
    {
        DB::beginTransaction(); // Start database transaction for temporarily storing data.
        try {
            // 1. Find existing photo album
            $photoAlbum->photo_category_id  = $request->photo_category_id;
            $photoAlbum->name               = $request->name;
            $photoAlbum->description        = $request->description;
            $photoAlbum->photographer_name  = $request->photographer_name;
            $photoAlbum->status             = $request->status;

            // Update main image if provided
            if ($request->hasFile('image')) {
                // If there image exists, `FileUploadHelper::uploadFile` will detect and delete it. Then upload new image.
                $photoAlbum->image = FileUploadHelper::uploadFile($request->image, $photoAlbum->image);
            }

            $photoAlbum->update();

            // 2. Update Album Photos - Other contents
            $existingContentIds = $photoAlbum->contents()->pluck('id')->toArray();
            $submittedContentIds = [];

            if ($request->has('contents')) {
                foreach ($request->contents as $key => $content) {
                    // Existing or new content
                    if (isset($content['id']) && in_array($content['id'], $existingContentIds)) {
                        $photo = Photo::find($content['id']);
                        $submittedContentIds[] = $photo->id;
                    } else {
                        $photo = new Photo();
                        $photo->photo_album_id = $photoAlbum->id;
                    }

                    $photo->caption = $content['caption'] ?? null;
                    $photo->photographer = $content['photographer'] ?? null;
                    $photo->show_at_home = $content['show_at_home'] ?? 2;
                    $photo->photo_of_the_day = $content['photo_of_the_day'] ?? 2;

                    // Only overwrite if new file uploaded
                    if ($request->hasFile("contents.$key.image")) {
                        // New upload → replace old
                        $photo->image = FileUploadHelper::uploadFile($request->file("contents.$key.image"), $photo->image);
                    } else {
                        // No new upload → keep old one from hidden field
                        $photo->image = $content['content_hidden_image'] ?? $photo->image;
                    }

                    $photo->save();
                }
            }

            // 3. Delete photos removed by user
            $toDelete = array_diff($existingContentIds, $submittedContentIds);
            if (!empty($toDelete)) {
                foreach ($toDelete as $delId) {
                    $content = Photo::find($delId);
                    // Delete image if exists for this content
                    if ($content->image && file_exists(public_path('uploads/'.$content->image))) {
                        unlink(public_path('uploads/'.$content->image));
                    }
                    $content->delete();
                }
            }

            DB::commit(); // Final database execution
            return redirect()->route('photo-album.index')->with('message', 'Photo album updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
