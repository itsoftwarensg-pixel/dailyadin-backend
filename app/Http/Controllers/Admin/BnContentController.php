<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BnContentStoreRequest;
use App\Models\BdDistrict;
use App\Models\BdUpazila;
use App\Models\BnCategory;
use App\Models\BnContent;
use App\Models\BnSpecialCategory;
use App\Models\BnSubCategory;
use App\Models\Writer;
use App\Services\ManagePositionService;
use App\Services\NewsCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\TagOperationService;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class BnContentController extends Controller
{
    protected $manageContentPositions; // manage content positions on create/edit/update
    protected $tagOperationService; // tag manage on create/update
    protected $contentHistoryService;

    public function __construct(
        ManagePositionService $positions,
        TagOperationService $tagOperationService,
        \App\Services\ContentHistoryService $contentHistoryService
    ) {
        $this->tagOperationService = $tagOperationService;
        $this->manageContentPositions = $positions;
        $this->contentHistoryService = $contentHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all active categories once
        $categories = BnCategory::where('status', StatusEnum::ACTIVE->value)->get();

        // Map of category id => name
        $categoryMap = $categories->pluck('name', 'id')->toArray();

        $searchType         = $request->type;
        $searchText         = $request->text;
        $searchShowContent  = $request->show_content;
        $searchCategory     = $request->category_id;

        $bnContents = BnContent::with(['tags', 'category', 'subCategory', 'writer', 'totalHits', 'editingBy', 'creator', 'updater'])
            ->when($searchText, function ($query) use ($searchType, $searchText) {
                switch ($searchType) {
                    case '1': // Content ID
                        if (is_numeric($searchText)) {
                            $query->where('id', $searchText);
                        }
                        break;

                    case '2': // Content Text (heading or content_details)
                        $query->where(function ($q) use ($searchText) {
                            $q->where('heading', 'like', "%{$searchText}%")
                                ->orWhere('content_details', 'like', "%{$searchText}%");
                        });
                        break;

                    case '3': // Content Writer (writer_id or writers column)
                        $query->where(function ($q) use ($searchText) {
                            $q->where('writer_id', $searchText)
                                ->orWhere('writers', 'like', "%{$searchText}%");
                        });
                        break;
                }
            })
//            ->when($searchCategory, function ($query) use ($searchCategory) {
//                $query->where('category_id', $searchCategory);
//            })

            ->when($searchCategory, function ($query) use ($searchCategory) {
                $query->where(function ($q) use ($searchCategory) {
                    $q->where('category_id', $searchCategory)
                        ->orWhereRaw("FIND_IN_SET(?, secondary_category_ids)", [$searchCategory]);
                });
            })
            ->when($searchShowContent, function ($query) use ($searchShowContent) {
                $query->where('show_content', $searchShowContent);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Map secondary category id, name for each content
        $bnContents->getCollection()->transform(function ($content) use ($categoryMap) {
            $ids = explode(',', $content->secondary_category_ids);
            $content->secondary_categories = collect($ids)
                ->map(fn($id) => [
                    'id' => $id,
                    'name' => $categoryMap[$id] ?? null
                ])
                ->filter(fn($item) => $item['name'] !== null)
                ->values()
                ->toArray();
            return $content;
        });

        return view('admin.bn-content.index', [
            'bnContents' => $bnContents,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Categories, special categories, writers, districts
        $categories = BnCategory::query()->where('status', StatusEnum::ACTIVE->value)->get();
        $specialCategories = BnSpecialCategory::where('status', StatusEnum::ACTIVE->value)->get();
        $writers = Writer::all();
        $districts = BdDistrict::all();

        return view('admin.bn-content.create', [
            'categories'                    => $categories,
            'specialCategories'             => $specialCategories,
            'districts'                     => $districts,
            'writers'                       => $writers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BnContentStoreRequest $request)
    {
        $bnContent = new BnContent();
        $bnContent->category_id                 = $request->category_id;
        $bnContent->secondary_category_ids      = $request->secondary_category_ids ? implode(",", $request->secondary_category_ids) : null;;
        $bnContent->category_home_position      = $request->category_home_position;
        $bnContent->category_inner_position     = $request->category_inner_position;
        $bnContent->sub_category_id             = $request->sub_category_id;
        // $bnContent->sub_category_id_pos         = $request->sub_category_id_pos; // TODO:: TBD - To be determined
        $bnContent->special_category_id         = $request->special_category_id;
        $bnContent->special_category_position   = $request->special_category_position;
        $bnContent->district_id                 = $request->district_id;
        // $bnContent->district_id_pos             = $bnContent->district_id_pos; // TODO:: TBD - To be determined
        $bnContent->upazila_id                  = $request->upazila_id;
        $bnContent->sub_heading                 = $request->sub_heading;
        $bnContent->heading                     = $request->heading;
        $bnContent->writer_id                   = $request->writer_id;
        $bnContent->writers                     = $request->writers; // Guest writer
        $bnContent->initial                     = $request->initial;
        $bnContent->meta_title                  = $request->meta_title;
        $bnContent->meta_keywords               = $request->meta_keywords;
        $bnContent->meta_description            = $request->meta_description;
        $bnContent->content_brief               = $request->content_brief;
        $bnContent->content_details             = $request->content_details; // News Details/Description
        $bnContent->bg_image_caption            = $request->bg_image_caption;
        $bnContent->sound_path                  = $request->sound_path;
        $bnContent->video_path                  = $request->video_path;
        $bnContent->show_content                = $request->show_content; // Published or Unpublished
        $bnContent->show_in_scroll              = $request->show_in_scroll;
        $bnContent->show_in_latest              = $request->show_in_latest;
        $bnContent->show_in_mobile              = $request->show_in_mobile;
        $bnContent->e_paper_date                = $bnContent->category_id == 51 ? $request->e_paper_date : null; // 51 = আজকের পত্রিকা
        $bnContent->ip_address                  = $request->ip();
        $bnContent->created_by                  = auth()->user()->id;
        $bnContent->updated_by                  = auth()->user()->id;
        $bnContent->tag_ids                     = $request->tags;

        // The form allows no image, but existing databases may require a string.
        $bnContent->bg_image_path = $request->hasFile('bg_image_path')
            ? FileUploadHelper::uploadFile($request->file('bg_image_path'))
            : '';

        DB::transaction(function () use ($bnContent, $request) {
            $bnContent->save();
            $this->contentHistoryService->store($bnContent);
            $this->tagOperationService->tagManageOnUpdate($request, $bnContent);
            $this->manageContentPositions->managePositionsOnContentCreateUpdate($bnContent, $request);
        });
        NewsCache::refresh($bnContent->id);

        return redirect(route('bn-content.index'))->with('message', 'Content inserted successfully');
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
    public function edit(BnContent $bnContent)
    {
        // Check if content is being edited by another user
        if ($bnContent->editable == 2 && ($bnContent->editingBy?->id !== auth()->user()->id)) {
            $editorName = $bnContent->editingBy?->name ?? 'another user';
            return redirect()->route('bn-content.index')
                ->with('message', 'The content ' . $bnContent->id . ' is currently being edited by ' . $editorName . '. Please try again later.');
        }

        // Mark as editing
        $this->setEditingStatus($bnContent, true);

        // Categories, associated subcategories and special categories
        $categories = BnCategory::query()->where('status', StatusEnum::ACTIVE->value)->get();
        $subCategories = BnSubCategory::query()->where('category_id', $bnContent->category_id)->where('status', StatusEnum::ACTIVE->value)->get();
        $specialCategories = BnSpecialCategory::where('status', StatusEnum::ACTIVE->value)->get();

        // Districts and associated upazilas
        $districts = BdDistrict::all();
        $upazilas = BdUpazila::query()->where('district_id',  $bnContent->district_id)->get();

        // IDs of secondary categories
        $secondaryCategoryIds = $bnContent->secondary_category_ids ? explode(',', $bnContent->secondary_category_ids) : [];

        // Writers
        $writers = Writer::all();

        /**-------------- START content index positions --------------*/
        // get index positions
        $positions = $this->manageContentPositions->contentIndexPositionsOnEdit($bnContent);
        $categoryHomeIndexPosition  = $positions['categoryHomeIndexPosition'];
        $categoryInnerIndexPosition = $positions['categoryInnerIndexPosition'];
        $leadIndexPosition          = $positions['leadIndexPosition'];
        /**-------------- END content index positions --------------*/

        return view('admin.bn-content.edit', [
            'bnContent'                     => $bnContent,
            'categories'                    => $categories,
            'specialCategories'             => $specialCategories,
            'subCategories'                 => $subCategories,
            'districts'                     => $districts,
            'upazilas'                      => $upazilas,
            'secondaryCategoryIds'          => $secondaryCategoryIds,
            'writers'                       => $writers,
            'categoryHomeIndexPosition'     => $categoryHomeIndexPosition,
            'categoryInnerIndexPosition'    => $categoryInnerIndexPosition,
            'leadIndexPosition'             => $leadIndexPosition,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BnContent $bnContent)
    {
        // Validate the request
        $request->validate(
            [
                'category_id'       => 'required',
                'heading'           => 'required',
                'writer_id'         => 'required',
                'e_paper_date'      => $request->category_id == 51 ? 'required' : '',
                'content_details'   => 'required',
                'bg_image_path'     => 'image|max:600',
            ]
        );

        $bnContent->category_id                 = $request->category_id;
        $bnContent->secondary_category_ids      = $request->secondary_category_ids ? implode(",", $request->secondary_category_ids) : null;;
        $bnContent->category_home_position      = $request->category_home_position;
        $bnContent->category_inner_position     = $request->category_inner_position;
        $bnContent->sub_category_id             = $request->sub_category_id;
        // $bnContent->sub_category_id_pos         = $request->sub_category_id_pos; // TODO:: TBD - To be determined
        $bnContent->special_category_id         = $request->special_category_id;
        $bnContent->special_category_position   = $request->special_category_position;
        $bnContent->district_id                 = $request->district_id;
        // $bnContent->district_id_pos             = $bnContent->district_id_pos; // TODO:: TBD - To be determined
        $bnContent->upazila_id                  = $request->upazila_id;
        $bnContent->sub_heading                 = $request->sub_heading;
        $bnContent->heading                     = $request->heading;
        $bnContent->writer_id                   = $request->writer_id;
        $bnContent->writers                     = $request->writers; // Guest writer
        $bnContent->initial                     = $request->initial;
        $bnContent->meta_title                  = $request->meta_title;
        $bnContent->meta_keywords               = $request->meta_keywords;
        $bnContent->meta_description            = $request->meta_description;
        $bnContent->content_brief               = $request->content_brief;
        $bnContent->content_details             = $request->content_details; // News Details/Description
        $bnContent->bg_image_caption            = $request->bg_image_caption;
        $bnContent->sound_path                  = $request->sound_path;
        $bnContent->video_path                  = $request->video_path;
        $bnContent->show_content                = $request->show_content; // Published or Unpublished
        $bnContent->show_in_scroll              = $request->show_in_scroll;
        $bnContent->show_in_latest              = $request->show_in_latest;
        $bnContent->show_in_mobile              = $request->show_in_mobile;
        $bnContent->e_paper_date                = $bnContent->category_id == 51 ? $request->e_paper_date : null; // 51 = আজকের পত্রিকা
        $bnContent->ip_address                  = $request->ip();
        $bnContent->updated_by                  = auth()->user()->id;
        $bnContent->tag_ids                     = $request->tags;

        if ($request->bg_image_path) {
            $bnContent->bg_image_path = FileUploadHelper::uploadFile($request->bg_image_path, $bnContent->bg_image_path);
        }

        // Calculate changes before saving to get correct old/new values
        $dirtyFields = $bnContent->getDirty();
        $changes = [];
        foreach ($dirtyFields as $field => $newValue) {
            $oldValue = $bnContent->getOriginal($field);
            // Only log if values are actually different (loose comparison to handle ID strings vs ints)
            if ($oldValue != $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        DB::transaction(function () use ($bnContent, $request, $changes) {
            $bnContent->save();
            $this->contentHistoryService->update($bnContent, $changes);
            $this->setEditingStatus($bnContent, false);
            $this->tagOperationService->tagManageOnUpdate($request, $bnContent);
            $this->manageContentPositions->managePositionsOnContentCreateUpdate($bnContent, $request);
        });
        NewsCache::refresh($bnContent->id);

        return redirect(route('bn-content.index', ['page' => $request->page]))->with('message', 'Content updated successfully');
    }

    /**
     * Cancel editing for the given content.
     * Releases the editing lock and marks it as not being edited.
     *
     * @param BnContent $bnContent
     * @return RedirectResponse
     */
    public function cancel(BnContent $bnContent)
    {
        $this->setEditingStatus($bnContent, false);
        return redirect(route('bn-content.index'))->with('message', 'Cancelled content editing!');
    }

    /**
     * Display a listing of the resource.
     */
    public function uneditableList(Request $request)
    {
        $categories = BnCategory::where('status', StatusEnum::ACTIVE->value)->get();

        $searchType     = $request->type;
        $searchText     = $request->text;
        $searchCategory = $request->category_id;

        $bnContents = BnContent::with(['tags', 'category', 'subCategory', 'writer', 'totalHits', 'editingBy', 'updater'])
            ->when($searchText, function ($query) use ($searchType, $searchText) {
                switch ($searchType) {
                    case '1': // Content ID
                        if (is_numeric($searchText)) {
                            $query->where('id', $searchText);
                        }
                        break;

                    case '2': // Content Text (heading or content_details)
                        $query->where(function ($q) use ($searchText) {
                            $q->where('heading', 'like', "%{$searchText}%")
                                ->orWhere('content_details', 'like', "%{$searchText}%");
                        });
                        break;

                    case '3': // Content Writer (writer_id or writers column)
                        $query->where(function ($q) use ($searchText) {
                            $q->where('writer_id', $searchText)
                                ->orWhere('writers', 'like', "%{$searchText}%");
                        });
                        break;
                }
            })
            ->when($searchCategory, function ($query) use ($searchCategory) {
                $query->where('category_id', $searchCategory);
            })
            ->where('editable', 2)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.bn-content.uneditable', [
            'bnContents' => $bnContents,
            'categories' => $categories,
        ]);
    }

    /**
     * Cancel editing for the given content.
     * Releases the editing lock and marks it as not being edited.
     *
     * @param  BnContent  $bnContent
     * @return RedirectResponse|Redirector|null
     */
    public function cancelEditing(BnContent $bnContent)
    {
        $this->setEditingStatus($bnContent, false);
        return redirect(route('uneditableList'))->with('message', 'Cancelled content editing!');
    }

    /**
     * Toggle the content publish/unpublish status.
     *
     * @param $id
     * @return JsonResponse
     */
    public function toggleContentStatus($id)
    {
        $content = BnContent::findOrFail($id);
        $content->show_content = $content->show_content == 1 ? 2 : 1;

        // Calculate changes before saving
        $dirtyFields = $content->getDirty();
        $changes = [];
        foreach ($dirtyFields as $field => $newValue) {
            $oldValue = $content->getOriginal($field);
            // Only log if values are actually different
            if ($oldValue != $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        DB::transaction(function () use ($content, $changes) {
            $content->save();
            $this->contentHistoryService->update($content, $changes);
        });
        NewsCache::refresh($content->id);

        return response()->json([
            'success' => true,
            'show_content' => $content->show_content
        ]);
    }

    /**
     * Toggle the editing status of a content.
     *
     * @param BnContent $bnContent
     * @param bool $editing True to mark as editing, false to cancel editing
     * @return void
     */
    protected function setEditingStatus(BnContent $bnContent, bool $editing = true): void
    {
        if ($editing) {
            // Mark as currently being edited by the logged-in user
            $bnContent->editable = 2;
            $bnContent->editing_by = auth()->user()->name;
            $bnContent->editing_by_id = auth()->user()->id;
            $bnContent->editing_started_at = now();
        } else {
            // Cancel editing
            $bnContent->editable = 1;
            $bnContent->editing_by = null;
            $bnContent->editing_by_id = null;
            $bnContent->editing_started_at = null;
        }

        $bnContent->save(); // Persist changes
    }

    /**
     * Handle file uploads from CKEditor.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function ckeUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            try {
                // Use the helper to upload the file to public/uploads/FY/
                $fileName = FileUploadHelper::uploadFile($file);

                // Construct the absolute URL
                $fileUrl = asset('uploads/' . $fileName);

                // Support for modern JSON response (used by some plugins/configs)
                if ($request->input('responseType') === 'json') {
                    return response()->json([
                        'uploaded' => 1,
                        'fileName' => $fileName,
                        'url' => $fileUrl
                    ]);
                }

                // Default CKEditor 4 callback script
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $message = '';

                return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$fileUrl', '$message');</script>";
            } catch (\Exception $e) {
                if ($request->input('responseType') === 'json') {
                    return response()->json([
                        'uploaded' => 0,
                        'error' => ['message' => $e->getMessage()]
                    ]);
                }

                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $message = $e->getMessage();
                return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', '$message');</script>";
            }
        }

        return '';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
