<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BnContent extends Model
{
    use SoftDeletes;

    public function histories()
    {
        return $this->hasMany(BnContentHistory::class)->orderByDesc('created_at');
    }

    /**
     * Relationship: A content belongs to many tags
     */
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,          // Related model
            'bn_content_tag',    // Pivot table
            'bn_content_id',     // Foreign key on pivot table for this model
            'tag_id'             // Foreign key on pivot table for Tag model
        )->withTimestamps();
    }

    /**
     * Export ContentID and TagIDs to CSV
     * Only includes rows where TagIDs is not null and not empty
     * @param string|null $filePath Optional file path; defaults to storage/bn_contents_tagids.csv
     * @return string CSV file path
     */
    public static function exportTagIDsToCSV($filePath = null)
    {
        $filePath = $filePath ?: storage_path('bn_contents_tagids.csv');

        // Fetch rows with non-null and non-empty TagIDs
        $rows = self::whereNotNull('TagIDs')
            ->where('TagIDs', '<>', '')
            ->orderByDesc('ContentID')
            ->get(['ContentID', 'TagIDs']);

        // Start CSV content
        $csvData = "ContentID,TagIDs\n";

        foreach ($rows as $row) {
            $csvData .= "{$row->ContentID},\"{$row->TagIDs}\"\n";
        }

        // Write to file
        file_put_contents($filePath, $csvData);

        return $filePath;
    }


    /**
     * Get word count of content_details
     * usage in blade file: $bnContent->content_details_word_count
     */
    public function getContentDetailsWordCountAttribute()
    {
        $content = $this->content_details;
        // Remove HTML tags
        $textOnly = strip_tags($content);
        // Optional: Decode HTML entities
        $textOnly = html_entity_decode($textOnly, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Match all words (Unicode-aware)
        preg_match_all('/\p{L}+/u', $textOnly, $matches);
        // Count words
        return $wordCount = count($matches[0]);
    }


    /**
     * Get the category belongs to the bn_content.
     *
     * @return object
     */
    public function category()
    {
        return $this->belongsTo(BnCategory::class, 'category_id');
    }


    /**
     * Get the sub-category belongs to the bn_content.
     *
     * @return object
     */
    public function subCategory()
    {
        return $this->belongsTo(BnSubCategory::class, 'sub_category_id');
    }


    /**
     * Get the sub-category belongs to the bn_content.
     *
     * @return object
     */
    public function writer()
    {
        return $this->belongsTo(Writer::class, 'writer_id');
    }


    /**
     * Get the editing-by user belongs to the bn_content.
     *
     * @return object
     */
    public function editingBy()
    {
        return $this->belongsTo(User::class, 'editing_by_id');
    }

    /**
     * Get the creator user belongs to the bn_content.
     *
     * @return object
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater user belongs to the bn_content.
     *
     * @return object
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    /**
     * Get total_hits of the specified content
     * usage in blade file: $bnContent->totalHits->total_hits
     */
    public function totalHits()
    {
        return $this->hasOne(BnTotalHit::class, 'bn_content_id');
    }

    public function getHitsAttribute()
    {
        return $this->totalHits?->total_hits ?? 0;
    }

    /**
     * Relationship: A content belongs to a district
     */
    public function district()
    {
        return $this->belongsTo(BdDistrict::class, 'district_id');
    }

    /**
     * Relationship: A content belongs to an upazila
     */
    public function upazila()
    {
        return $this->belongsTo(BdUpazila::class, 'upazila_id');
    }

    /**
     * Scope: Filter by category (main or secondary)
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where(function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId)
                ->orWhereRaw('FIND_IN_SET(?, secondary_category_ids)', [$categoryId]);
        });
    }

    /**
     * Scope: Filter by sub-category
     */
    public function scopeBySubCategory($query, $subCategoryId)
    {
        return $query->where('sub_category_id', $subCategoryId);
    }

    /**
     * Scope: Filter by district
     */
    public function scopeByDistrict($query, $districtId)
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Scope: Filter by upazila
     */
    public function scopeByUpazila($query, $upazilaId)
    {
        return $query->where('upazila_id', $upazilaId);
    }

    /**
     * Scope: Filter by division
     */
    public function scopeByDivision($query, $divisionId)
    {
        return $query->whereHas('district', function ($q) use ($divisionId) {
            $q->where('division_id', $divisionId);
        });
    }

    /**
     * Scope: Filter by writer
     */
    public function scopeByWriter($query, $writerId)
    {
        return $query->where('writer_id', $writerId);
    }

    /**
     * Scope: Search by keyword
     */
    public function scopeByKeyword($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('heading', 'LIKE', "%{$keyword}%")
                ->orWhere('content_details', 'LIKE', "%{$keyword}%");
        });
    }

    /**
     * Scope: Filter by date
     */
    public function scopeByDate($query, $start, $end = null)
    {
        if ($end) {
            return $query->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        }
        return $query->whereDate('created_at', $start);
    }

    /**
     * Scope: Filter by Tag (ID or Slug)
     */
    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            if (is_numeric($tagId)) {
                $q->where('tags.id', $tagId);
            } else {
                $q->where('tags.alias', $tagId);
            }
        });
    }

    /**
     * Get the frontend URL for the content
     * usage in blade file: $bnContent->frontend_url
     */
    public function getFrontendUrlAttribute()
    {
        return rtrim(config('app.frontend_url'), '/') . '/' . ($this->category->alias ?? 'news') . '/' . $this->id;
    }
}
