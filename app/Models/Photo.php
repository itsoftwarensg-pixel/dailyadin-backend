<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'photo_album_id',
        'image',
        'caption',
        'photographer',
        'show_at_home',
        'photo_of_the_day',
    ];

    /**
     * Each content belongs to a PhotoAlbum
     */
    public function photoAlbum()
    {
        return $this->belongsTo(PhotoAlbum::class, 'photo_album_id', 'id');
    }
}
