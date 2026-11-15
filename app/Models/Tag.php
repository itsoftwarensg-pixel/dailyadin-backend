<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'alias',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'sorting',
        'image',
        'status',
    ];

    /**
     * Many-to-Many relationship: A tag belongs to many contents
     */
    public function contents()
    {
        return $this->belongsToMany(
            BnContent::class,   // Related model
            'bn_content_tag',   // Pivot table
            'tag_id',           // Foreign key on pivot table for this model
            'bn_content_id'     // Foreign key on pivot table for BnContent model
        )->withTimestamps();
    }

    /**
     * Scope to search by name or alias
     */
    public function scopeSearch($query, $term)
    {
        return $query->when($term, function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('alias', 'like', "%{$term}%");
        });
    }

    /**
     * Automatically flush Redis tag cache when a resource is created, updated, or deleted.
     */
    /*protected static function booted()
    {
        // Flush all Redis cache for tags when created/updated/deleted
        static::created(fn ($tag) => self::flushTagsCache());
        static::updated(fn ($tag) => self::flushTagsCache());
        static::deleted(fn ($tag) => self::flushTagsCache());
    }*/

    /**
     * Flush all Redis cache for tags
     */
    /*protected static function flushTagsCache()
    {
        $keys = Redis::keys(config('cache.prefix').'tags:*');
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }*/
}
