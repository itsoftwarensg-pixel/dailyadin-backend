<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebstoryRequest;
use App\Models\Webstory;
use App\Models\WebstoryCategory;
use App\Models\WebstoryContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebstoryController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index()
    {
        $webstories = Webstory::latest('id')->paginate(15)->withQueryString();
        return view('admin.webstory.webstory.index', [
            'webstories' => $webstories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $webstoryCategories = WebstoryCategory::all();
        return view('admin.webstory.webstory.create', [
            'webstoryCategories' => $webstoryCategories,
        ]);
    }

    /**
     * Store a newly created webstory + contents.
     */
    public function store(WebstoryRequest $request)
    {
        DB::beginTransaction(); // Start database transaction for temporarily storing data.
        try {
            // 1. Main webstory - Featured
            $webstory = new Webstory();
            $webstory->webstory_category_id = $request->webstory_category_id;
            $webstory->title                = $request->title;
            $webstory->credit_by            = $request->credit_by;

            if ($request->image) {
                $webstory->image = FileUploadHelper::uploadFile($request->image, $webstory->image);
            }

            $webstory->save();

            // 2. Other webstory contents
            if ($request->has('contents')) {
                foreach ($request->contents as $content) {
                    $item = new WebstoryContent();
                    $item->webstory_id  = $webstory->id;
                    $item->caption      = $content['caption'] ?? null;
                    $item->description  = $content['description'] ?? null;

                    if (isset($content['image'])) {
                        $item->image = FileUploadHelper::uploadFile($content['image'], $item->image);
                    }

                    $item->save();
                }
            }

            DB::commit(); // Final database execution
            return redirect()->route('webstory.index')->with('message', 'Webstory created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing a resource.
     */
    public function edit(Webstory $webstory)
    {
        // Eager load contents
        $webstory->load('contents');

        $webstoryCategories = WebstoryCategory::all();
        return view('admin.webstory.webstory.edit', [
            'webstory' => $webstory,
            'webstoryCategories' => $webstoryCategories,
        ]);
    }

    /**
     * Update an existing webstory + contents.
     */
    public function update(WebstoryRequest $request, Webstory $webstory)
    {
        DB::beginTransaction(); // Start database transaction for temporarily storing data.
        try {
            // 1. Find existing webstory
            $webstory->webstory_category_id = $request->webstory_category_id;
            $webstory->title                = $request->title;
            $webstory->credit_by            = $request->credit_by;
            $webstory->status               = $request->status;

            // Update main image if provided
            if ($request->hasFile('image')) {
                // If there image exists, `FileUploadHelper::uploadFile` will detect and delete it. Then upload new image.
                $webstory->image = FileUploadHelper::uploadFile($request->image, $webstory->image);
            }

            $webstory->update();

            // 2. Update webstory contents
            $existingContentIds = $webstory->contents()->pluck('id')->toArray();
            $submittedContentIds = [];

            if ($request->has('contents')) {
                foreach ($request->contents as $key => $content) {
                    // If content has an id, update existing content
                    if (isset($content['id']) && in_array($content['id'], $existingContentIds)) {
                        $webstoryContent = WebstoryContent::find($content['id']);
                        $submittedContentIds[] = $webstoryContent->id;
                    } else {
                        // New content
                        $webstoryContent = new WebstoryContent();
                        $webstoryContent->webstory_id = $webstory->id;
                    }

                    $webstoryContent->caption      = $content['caption'] ?? null;
                    $webstoryContent->description  = $content['description'] ?? null;

                    // Only overwrite if new file uploaded
                    if ($request->hasFile("contents.$key.image")) {
                        // New upload → replace old
                        $webstoryContent->image = FileUploadHelper::uploadFile($content['image'], $webstoryContent->image);
                    } else {
                        // No new upload → keep old one from hidden field
                        $webstoryContent->image = $content['content_hidden_image'] ?? $webstoryContent->image;
                    }

                    $webstoryContent->save();
                }
            }

            // 3. Delete contents removed by user
            $toDelete = array_diff($existingContentIds, $submittedContentIds);
            if (!empty($toDelete)) {
                foreach ($toDelete as $delId) {
                    $content = WebstoryContent::find($delId);
                    // Delete image if exists for this content
                    if ($content->image && file_exists(public_path('uploads/'.$content->image))) {
                        unlink(public_path('uploads/'.$content->image));
                    }
                    $content->delete();
                }
            }

            DB::commit(); // Final database execution
            return redirect()->route('webstory.index')->with('message', 'Webstory updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

}
