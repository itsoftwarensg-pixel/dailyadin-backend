<?php

namespace App\Http\Controllers\Admin;

use App\Models\BnContent;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function show($id)
    {
        $bnContent = BnContent::with(['creator', 'histories.user'])->findOrFail($id);

        $histories = $bnContent->histories;
        $hasCreateAction = $histories->contains(function ($h) {
            return in_array($h->action, ['insert', 'create', 'store']);
        });

        $idToNameMap = $this->getHistoryIdToNameMap($bnContent);

        return view('admin.bn-content.history', compact('bnContent', 'hasCreateAction', 'idToNameMap'));
    }

    private function getHistoryIdToNameMap($bnContent)
    {
        $ids = [
            'category_id' => [],
            'sub_category_id' => [],
            'special_category_id' => [],
            'writer_id' => [],
            'district_id' => [],
            'upazila_id' => [],
            'user_id' => [], // for created_by, updated_by, editing_by_id
            'TagIDs' => [],
        ];

        foreach ($bnContent->histories as $history) {
            if (!$history->changes) continue;

            foreach ($history->changes as $field => $change) {
                foreach (['old', 'new'] as $key) {
                    $val = $change[$key];
                    if (is_null($val) || $val === '') continue;

                    if (in_array($field, ['category_id', 'sub_category_id', 'special_category_id', 'writer_id', 'district_id', 'upazila_id'])) {
                        $ids[$field][] = $val;
                    } elseif (in_array($field, ['created_by', 'updated_by', 'editing_by_id'])) {
                        $ids['user_id'][] = $val;
                    } elseif (in_array($field, ['tag_ids', 'TagIDs'])) {
                        $tagIds = explode(',', $val);
                        foreach ($tagIds as $tid) {
                            if (is_numeric($tid)) $ids['TagIDs'][] = $tid;
                        }
                    } elseif ($field === 'secondary_category_ids') {
                        $catIds = explode(',', $val);
                        foreach ($catIds as $cid) {
                            if (is_numeric($cid)) $ids['category_id'][] = $cid;
                        }
                    }
                }
            }
        }

        return [
            'category_id' => \App\Models\BnCategory::whereIn('id', array_unique($ids['category_id']))->pluck('name', 'id')->toArray(),
            'sub_category_id' => \App\Models\BnSubCategory::whereIn('id', array_unique($ids['sub_category_id']))->pluck('name', 'id')->toArray(),
            'special_category_id' => \App\Models\BnSpecialCategory::whereIn('id', array_unique($ids['special_category_id']))->pluck('name', 'id')->toArray(),
            'writer_id' => \App\Models\Writer::whereIn('id', array_unique($ids['writer_id']))->pluck('name', 'id')->toArray(),
            'district_id' => \App\Models\BdDistrict::whereIn('id', array_unique($ids['district_id']))->pluck('name', 'id')->toArray(),
            'upazila_id' => \App\Models\BdUpazila::whereIn('id', array_unique($ids['upazila_id']))->pluck('name', 'id')->toArray(),
            'user_id' => \App\Models\User::whereIn('id', array_unique($ids['user_id']))->pluck('name', 'id')->toArray(),
            'TagIDs' => \App\Models\Tag::whereIn('id', array_unique($ids['TagIDs']))->pluck('name', 'id')->toArray(),
        ];
    }

    public function summary($id)
    {
        $bnContent = BnContent::with(['creator', 'histories.user'])->findOrFail($id);

        $histories = $bnContent->histories;
        $hasCreateAction = $histories->contains(function ($h) {
            return in_array($h->action, ['insert', 'create', 'store']);
        });

        $data = $histories->map(function ($history) {
            $isCreate = in_array($history->action, ['insert', 'create', 'store']);
            return [
                'time' => \App\Helpers\BengaliDateHelper::formatBengaliDateTime($history->created_at),
                'updated_by' => !$isCreate ? ($history->user->name ?? 'N/A') : '--',
                'created_by' => $isCreate ? ($history->user->name ?? 'N/A') : '--',
            ];
        })->toArray();

        // If no explicit create record exists in history, add one from the content's own creation data
        if (!$hasCreateAction) {
            $data[] = [
                'time' => \App\Helpers\BengaliDateHelper::formatBengaliDateTime($bnContent->created_at),
                'updated_by' => '--',
                'created_by' => $bnContent->creator->name ?? $bnContent->created_by ?? 'N/A',
            ];
        }

        return response()->json([
            'content_id' => $id,
            'histories' => $data
        ]);
    }
}
