<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebstoryContent extends Model
{
    protected $fillable = [
        'webstory_id',
        'image',
        'caption',
        'description',
        'status',
    ];

    /**
     * Each content belongs to a webstory
     */
    public function webstory()
    {
        return $this->belongsTo(Webstory::class, 'webstory_id', 'id');
    }
}
