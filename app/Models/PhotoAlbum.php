<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoAlbum extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'photo_category_id',
        'name',
        'name_bn',
        'description',
        'description_bn',
        'photographer_name',
        'photographer_name_bn',
        'image',
        'status',
    ];

    /**
     * Relationship: Album belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(PhotoCategory::class, 'photo_category_id');
    }

    /**
     * One PhotoAlbum has many contents
     */
    public function contents()
    {
        return $this->hasMany(Photo::class, 'photo_album_id', 'id');
    }
}
