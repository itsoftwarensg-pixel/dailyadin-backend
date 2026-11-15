<?php
namespace App\Services;

use App\Models\BnContent;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagOperationService{

    /**
     * Tag manage on updating for the given content.
     *
     * @param Request $request
     * @param BnContent $bnContent
     * @return void
     */
    public function tagManageOnUpdate(Request $request, BnContent $bnContent)
    {
        /**-------------- START TAG MANAGEMENT --------------*/
        // Get old and new tag IDs
        $previousTagIds = $bnContent->tags->pluck('id')->toArray(); // bn_content_tag table
        $newTagIds = $request->input('tags') ? explode(',', $request->input('tags')) : []; // 1,2,4

        // Find which tags were added and removed
        $addedTags = array_diff($newTagIds, $previousTagIds);
        $removedTags = array_diff($previousTagIds, $newTagIds);

        // Sync tags (pivot table)
        $bnContent->tags()->sync($newTagIds); // if new tags are added, new records will be created, if old tags are removed, old records will be deleted

        // Update contents_count
        if (!empty($addedTags)) {
            Tag::whereIn('id', $addedTags)->increment('contents_count'); // increment: `contents_count` = `contents_count` + 1
        }

        if (!empty($removedTags)) {
            Tag::whereIn('id', $removedTags)->decrement('contents_count'); // decrement: `contents_count` = `contents_count` - 1
        }
        /**-------------- END TAG MANAGEMENT --------------*/
    }
}
