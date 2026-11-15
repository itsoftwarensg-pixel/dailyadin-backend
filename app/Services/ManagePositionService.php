<?php

namespace App\Services;

use App\Enums\BnManagePositionEnum;
use App\Models\BnContent;
use App\Models\BnManagePosition;
use Illuminate\Http\Request;

class ManagePositionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get index positions (category_home, category_inner, lead) for a content.
     *
     * @param BnContent $bnContent
     * @return array
     */
    public function contentIndexPositionsOnEdit(BnContent $bnContent): array
    {
        $positions              = $this->getContentPositions($bnContent);
        $categoryHomePosition   = $positions['categoryHome'];
        $categoryInnerPosition  = $positions['categoryInner'];
        $leadPosition           = $positions['lead'];

        $categoryHomeIndexPosition  = null;
        $categoryInnerIndexPosition = null;
        $leadIndexPosition          = null;

        // Category Home
        if ($categoryHomePosition) {
            $ids   = $categoryHomePosition->ids ? array_map('intval', explode(',', $categoryHomePosition->ids)) : [];
            $index = array_search($bnContent->id, $ids, true);
            $categoryHomeIndexPosition = $index !== false ? $index + 2 : null;
        }

        // Category Inner
        if ($categoryInnerPosition) {
            $ids   = $categoryInnerPosition->ids ? array_map('intval', explode(',', $categoryInnerPosition->ids)) : [];
            $index = array_search($bnContent->id, $ids, true);
            $categoryInnerIndexPosition = $index !== false ? $index + 2 : null;
        }

        // Lead
        if ($leadPosition) {
            $ids   = $leadPosition->ids ? array_map('intval', explode(',', $leadPosition->ids)) : [];
            $index = array_search($bnContent->id, $ids, true);
            $leadIndexPosition = $index !== false ? $index + 2 : null;
        }

        return [
            'categoryHomeIndexPosition'  => $categoryHomeIndexPosition,
            'categoryInnerIndexPosition' => $categoryInnerIndexPosition,
            'leadIndexPosition'          => $leadIndexPosition,
        ];
    }

    /**
     * Manage both category_home and category_inner positions for content create/update.
     *
     * @param BnContent $bnContent
     * @param Request $request
     */
    public function managePositionsOnContentCreateUpdate(BnContent $bnContent, Request $request)
    {
        // Initialize positions
        $positions = $this->getContentPositions($bnContent);

        // Store positions variables to category_home, category_inner, lead and allPositions
        $allPositions           = $positions['allPositions'];
        $categoryHomePosition   = $positions['categoryHome'];
        $categoryInnerPosition  = $positions['categoryInner'];
        $leadPosition           = $positions['lead'];

        // MANAGE category_inner POSITIONS
        $this->updateManagePosition(
            $categoryInnerPosition,
            $bnContent,
            $bnContent->category_inner_position,
            $request,
            BnManagePositionEnum::CATEGORY_INNER_POSITION->value,
            $allPositions
        );

        // MANAGE category_home POSITIONS
        $this->updateManagePosition(
            $categoryHomePosition,
            $bnContent,
            $bnContent->category_home_position,
            $request,
            BnManagePositionEnum::CATEGORY_HOME_POSITION->value,
            $allPositions
        );

        // MANAGE special/lead POSITIONS (lead)
        $this->updateManagePosition(
            $leadPosition,
            $bnContent,
            $bnContent->special_category_position
        );
    }

    /**
     * Update content position for a given category and position type.
     *
     * @param BnManagePosition $position
     * @param BnContent $bnContent
     * @param int $categoryPosition
     * @param Request $request
     * @param string $positionType
     * @param $allPositions
     */
    protected function updateManagePosition(
        BnManagePosition $position,
        BnContent $bnContent,
        int $categoryPosition,
        Request $request = null,
        string $positionType = null,
        $allPositions = null
    ) {
        $contentId = $bnContent->id;
        $showContent = $bnContent->show_content == 1;
        $maxContent = $position->max_content ?? 9;

        // Convert comma-separated IDs to array
        $ids = $position->ids ? explode(',', $position->ids) : [];

        // Capture current index before removing (used to restore position if no new position is requested)
        $currentIndex = array_search((string) $contentId, $ids);

        // Remove current content ID first
        $ids = array_values(array_filter($ids, fn($id) => $id != $contentId));

        $isLeadPosition = $position->position_type === BnManagePositionEnum::LEAD_POSITION->value;
        $positionUnchanged = $currentIndex !== false && ($categoryPosition <= 1 || $categoryPosition == $currentIndex + 2);

        // If lead position hasn't changed, restore at original index using splice (not replace)
        if ($showContent && $isLeadPosition && $positionUnchanged) {
            array_splice($ids, $currentIndex, 0, $contentId);
        } elseif ($showContent && $categoryPosition > 1) {
            // Zero-based index
            $insertIndex = $categoryPosition - 2;

            // If 'lead' (Special Category) position, replace existing ID at target index
            if($position->position_type == BnManagePositionEnum::LEAD_POSITION->value){
                // Replace existing ID at target index if there is one
                if (isset($ids[$insertIndex])) {
                    $ids[$insertIndex] = $contentId;
                } else {
                    // If target index does not exist, append at the end
                    $ids[] = $contentId;
                }
            }

            // No replace. If requested index is beyond current length, append at the end
            else{
                if ($insertIndex >= count($ids)) {
                    $ids[] = $contentId;
                } else {
                    array_splice($ids, $insertIndex, 0, $contentId);
                }
            }

            // Optional: limit to maxContent
            if (count($ids) > $maxContent) {
                $ids = array_slice($ids, 0, $maxContent);
            }
        }

        // Save updated IDs
        $position->ids = implode(',', $ids);
        $position->save();

        /**---- Remove the content ID from the previous category position. Only for these (category_home, category_inner) positions ----*/
        if($positionType  && $bnContent->category_id != $request->previous_category_id){
            // Filter the previous category for category_home position
            $previousCategoryHomePosition = $allPositions->firstWhere(fn($pos) =>
                $pos->position_type === $positionType && $pos->position_type_id == $request->previous_category_id
            );

            // If the previous category has a category_home position, remove the content ID from it
            if ($previousCategoryHomePosition) {
                $ids = collect(explode(',', $previousCategoryHomePosition->ids))
                    ->filter(fn($id) => $id != $bnContent->id) // remove current content id
                    ->implode(',');

                $previousCategoryHomePosition->ids = $ids;
                $previousCategoryHomePosition->save();
            }
        }
    }

    /**
     * Fetch all positions for the content
     *
     * @param BnContent $bnContent
     * @return array
     */
    public function getContentPositions(BnContent $bnContent): array
    {
        // Fetch all relevant positions in one query
        $allPositions = BnManagePosition::query()
            ->whereIn('position_type', [
                BnManagePositionEnum::CATEGORY_HOME_POSITION->value,
                BnManagePositionEnum::CATEGORY_INNER_POSITION->value,
                BnManagePositionEnum::LEAD_POSITION->value,
            ])
            ->get();

        return [
            'categoryHome'  => $allPositions->firstWhere(fn($pos) =>
                $pos->position_type === BnManagePositionEnum::CATEGORY_HOME_POSITION->value && $pos->position_type_id == $bnContent->category_id
            ),
            'categoryInner' => $allPositions->firstWhere(fn($pos) =>
                $pos->position_type === BnManagePositionEnum::CATEGORY_INNER_POSITION->value && $pos->position_type_id == $bnContent->category_id
            ),
            'lead'          => $allPositions->firstWhere('position_type', BnManagePositionEnum::LEAD_POSITION->value),
            'allPositions'  => $allPositions,
        ];
    }
}
