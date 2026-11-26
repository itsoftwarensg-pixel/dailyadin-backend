<?php

namespace App\Http\Controllers\Admin;

use App\Services\NewsCache;

use App\Enums\StatusEnum;
use App\Models\Tag;
use App\Models\BnContent;
use App\Models\BnCategory;
use Illuminate\Http\Request;
use App\Models\BnManagePosition;
use App\Enums\BnManagePositionEnum;
use App\Http\Controllers\Controller;
use App\Models\BnManageSpecialEvent;

class BnManagePositionController extends Controller
{
    public function index()
    {
        $positions = BnManagePosition::all();
        $enums = BnManagePositionEnum::cases();

        return view('admin.manage-position.index', compact('positions', 'enums'));
    }

    public function lead()
    {
        $positionType = BnManagePositionEnum::LEAD_POSITION;
        $position = BnManagePosition::where('position_type', $positionType->value)->first();
        $contents = $this->getContentsFromIds($position?->ids);

        return view('admin.manage-position.lead', compact('position', 'positionType', 'contents'));
    }

    public function special()
    {
        $positionType = BnManagePositionEnum::SPECIAL;
        $position = BnManagePosition::where('position_type', $positionType->value)->first();
        $contents = $this->getContentsFromIds($position?->ids);

        return view('admin.manage-position.special', compact('position', 'positionType', 'contents'));
    }

    /* public function categoryHome(Request $request)
    {
        $positionType = BnManagePositionEnum::CATEGORY_HOME_POSITION;
        $categories = BnCategory::query()->where('status', StatusEnum::ACTIVE->value)->get();
        $categoryId = $request->get('category_id') ?: $categories->first()?->id;

        $position = BnManagePosition::where('position_type', $positionType->value)
            ->where('position_type_id', $categoryId)
            ->first();
        $contents = $this->getContentsFromIds($position?->ids);

        return view('admin.manage-position.category-home', compact('position', 'positionType', 'contents', 'categories', 'categoryId'));
    } */

    /* public function categoryInner(Request $request)
    {
        $positionType = BnManagePositionEnum::CATEGORY_INNER_POSITION;
        $categories = BnCategory::query()->where('status', StatusEnum::ACTIVE->value)->get();
        $categoryId = $request->get('category_id') ?: $categories->first()?->id;

        $position = BnManagePosition::where('position_type', $positionType->value)
            ->where('position_type_id', $categoryId)
            ->first();
        $contents = $this->getContentsFromIds($position?->ids);

        return view('admin.manage-position.category-inner', compact('position', 'positionType', 'contents', 'categories', 'categoryId'));
    } */

    public function scroll()
    {
        $positionType = BnManagePositionEnum::SCROLL_POSITION;
        $position = BnManagePosition::where('position_type', $positionType->value)->first();
        $contents = $this->getContentsFromIds($position?->ids);

        return view('admin.manage-position.scroll', compact('position', 'positionType', 'contents'));
    }

    public function specialEvent()
    {
        $specialEvents = BnManageSpecialEvent::orderBy('id', 'desc')->get();
        return view('admin.manage-position.special-event', compact('specialEvents'));
    }

    public function createSpecialEvent()
    {
        return view('admin.manage-position.special-event-form', ['specialEvent' => null, 'contents' => []]);
    }

    public function editSpecialEvent($id)
    {
        $specialEvent = BnManageSpecialEvent::findOrFail($id);
        $contents = $this->getContentsFromIds($specialEvent->news_ids);
        return view('admin.manage-position.special-event-form', compact('specialEvent', 'contents'));
    }

    public function toggleSpecialEventStatus($id)
    {
        $specialEvent = BnManageSpecialEvent::findOrFail($id);
        $specialEvent->active_status = $specialEvent->active_status == 1 ? 2 : 1;
        $specialEvent->save();
        NewsCache::refresh();

        return response()->json([
            'success' => true,
            'active_status' => $specialEvent->active_status
        ]);
    }

    public function categoryCombined(Request $request)
    {
        $categories = BnCategory::query()->where('status', StatusEnum::ACTIVE->value)->get();
        $categoryId = $request->get('category_id') ?: $categories->first()?->id;

        $homePosition = BnManagePosition::where('position_type', BnManagePositionEnum::CATEGORY_HOME_POSITION->value)
            ->where('position_type_id', $categoryId)
            ->first();
        $homeContents = $this->getContentsFromIds($homePosition?->ids);

        $innerPosition = BnManagePosition::where('position_type', BnManagePositionEnum::CATEGORY_INNER_POSITION->value)
            ->where('position_type_id', $categoryId)
            ->first();
        $innerContents = $this->getContentsFromIds($innerPosition?->ids);

        return view('admin.manage-position.category-combined', compact(
            'categories', 'categoryId',
            'homePosition', 'homeContents',
            'innerPosition', 'innerContents'
        ));
    }

    public function trendingTag()
    {
        $positionType = BnManagePositionEnum::TRENDING_TAG_POSITION;
        $position = BnManagePosition::where('position_type', $positionType->value)->first();
        $tags = $this->getTagsFromIds($position?->ids);

        return view('admin.manage-position.trending-tag', compact('position', 'positionType', 'tags'));
    }

    protected function getContentsFromIds($idsString)
    {
        if (empty($idsString)) {
            return [];
        }

        $ids = array_filter(explode(',', $idsString));
        if (empty($ids)) {
            return [];
        }

        return BnContent::whereIn('id', $ids)
            ->orderByRaw('FIELD(id, '.implode(',', array_fill(0, count($ids), '?')).')', $ids)
            ->get();
    }

    protected function getTagsFromIds($idsString)
    {
        if (empty($idsString)) {
            return [];
        }

        $ids = array_filter(explode(',', $idsString));
        if (empty($ids)) {
            return [];
        }

        return Tag::whereIn('id', $ids)
            ->orderByRaw('FIELD(id, '.implode(',', array_fill(0, count($ids), '?')).')', $ids)
            ->get();
    }

    public function show(Request $request, $type)
    {
        // Keep this for backward compatibility or redirect if needed
        $underscoreType = str_replace('-', '_', $type);
        return redirect()->route('manage-position.' . $type);
    }

    public function store(Request $request)
    {
        // Handle Special Event separately
        if ($request->position_type === BnManagePositionEnum::SPECIAL_EVENT_POSITION->value) {
            $request->validate([
                'news_ids' => 'nullable|string',
                'event_name' => 'required|string|max:255',
                'event_url' => 'nullable|string|max:255',
                // 'active_status' => 'nullable|integer|in:1,2' // Excluded the validation to avoid validation error.,
            ]);

            try {
                $data = [
                    'news_ids' => $request->news_ids,
                    'event_name' => $request->event_name,
                    'event_url' => $request->event_url,
                    'active_status' => $request->has('active_status') ? 1 : 2,
                ];

                if ($request->event_id) {
                    // Update existing
                    $specialEvent = BnManageSpecialEvent::findOrFail($request->event_id);
                    $specialEvent->update($data);
                    $message = 'Special Event updated successfully';
                } else {
                    // Create new
                    BnManageSpecialEvent::create($data);
                    $message = 'Special Event created successfully';
                }

                NewsCache::refresh();

                return redirect()->route('manage-position.special-event')->with('message', $message);
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Failed to save event: ' . $e->getMessage());
            }
        }

        // Handle other position types
        $request->validate([
            'position_type' => 'required',
            'ids' => 'nullable|string',
            'position_type_id' => 'nullable|integer',
            'max_content' => 'nullable|integer',
        ]);

        BnManagePosition::updateOrCreate(
            [
                'position_type' => $request->position_type,
                'position_type_id' => $request->position_type_id ?: null,
            ],
            [
                'ids' => $request->ids,
                'max_content' => $request->max_content,
            ],
        );

        NewsCache::refresh();

        return redirect()->back()->with('message', 'Position updated successfully');
    }

    public function searchContent(Request $request)
    {
        $term = $request->get('term');
        $contents = BnContent::where('show_content', 1)
            ->where(function ($query) use ($term) {
                $query->where('id', 'LIKE', '%' . $term . '%')->orWhere('heading', 'LIKE', '%' . $term . '%');
            })
            ->limit(10)
            ->get();

        $results = [];
        foreach ($contents as $content) {
            $results[] = [
                'id' => $content->id,
                'value' => $content->id . ' | ' . $content->heading,
                'label' => $content->id . ' | ' . $content->heading,
            ];
        }

        return response()->json($results);
    }

    public function searchTag(Request $request)
    {
        $term = $request->get('term');
        $tags = Tag::where(function ($query) use ($term) {
                $query->where('id', 'LIKE', '%' . $term . '%')
                    ->orWhere('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('alias', 'LIKE', '%' . $term . '%');
            })
            ->limit(10)
            ->get();

        $results = [];
        foreach ($tags as $tag) {
            $results[] = [
                'id' => $tag->id,
                'value' => $tag->id . ' | ' . $tag->name,
                'label' => $tag->id . ' | ' . $tag->name,
            ];
        }

        return response()->json($results);
    }
}
