<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class BnCategory extends Model
{
    use SoftDeletes;

//    /**
//     * Automatically flush Redis tag cache when a resource is created, updated, or deleted.
//     */
//    protected static function booted()
//    {
//        // Flush all Redis cache for tags when created/updated/deleted
//        static::created(fn ($category) => self::flushCache());
//        static::updated(fn ($category) => self::flushCache());
//        static::deleted(fn ($category) => self::flushCache());
//    }
//
//    /**
//     * Flush all Redis cache for categories
//     */
//    protected static function flushCache()
//    {
//        $keys = Redis::keys(config('cache.prefix').'categories:*');
//        if (!empty($keys)) {
//            Redis::del($keys);
//        }
//    }
    /**
     * Relationship: A category has many contents
     */
    public function contents()
    {
        return $this->hasMany(BnContent::class, 'category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(BnSubCategory::class, 'category_id')->where('status', StatusEnum::ACTIVE->value)->orderBy('sorting', 'asc');
    }
}
