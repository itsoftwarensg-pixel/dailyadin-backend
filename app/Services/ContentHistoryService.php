<?php

namespace App\Services;

use App\Models\BnContent;
use App\Models\BnContentHistory;

class ContentHistoryService
{
    /**
     * Store history for content creation.
     */
    public function store(BnContent $bnContent): void
    {
        BnContentHistory::create([
            'bn_content_id' => $bnContent->id,
            'user_id' => auth()->id(),
            'action' => 'create',
            'changes' => null,
        ]);
    }

    /**
     * Store history for content update.
     */
    /**
     * Store history for content update.
     * @param BnContent $bnContent
     * @param array $changes Array of changes ['field' => ['old' => ..., 'new' => ...]]
     */
    public function update(BnContent $bnContent, array $changes): void
    {
        // Define fields to ignore
        $ignoredFields = [
            'created_at',
            'created_by',
            'created_by_old',
            'updated_by_old',
            'updated_at',
            'editable',
            'editing_by',
            'editing_by_id',
            'editing_started_at'
        ];

        // Filter out ignored fields from changes
        $filteredChanges = [];
        foreach ($changes as $key => $values) {
            if (in_array($key, $ignoredFields)) continue;
            $filteredChanges[$key] = $values;
        }

        if (!empty($filteredChanges)) {
            BnContentHistory::create([
                'bn_content_id' => $bnContent->id,
                'user_id' => auth()->id(),
                'action' => 'update',
                'changes' => $filteredChanges,
            ]);
        }
    }

    /**
     * Store history for content deletion.
     */
    public function delete(BnContent $bnContent): void
    {
        BnContentHistory::create([
            'bn_content_id' => $bnContent->id,
            'user_id' => auth()->id(),
            'action' => 'delete',
            'changes' => null,
        ]);
    }

    /**
     * Store history for content restoration.
     */
    public function restore(BnContent $bnContent): void
    {
        BnContentHistory::create([
            'bn_content_id' => $bnContent->id,
            'user_id' => auth()->id(),
            'action' => 'restore',
            'changes' => null,
        ]);
    }
}
