<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webstory extends Model
{
    protected $fillable = [
        'webstory_category_id',
        'title',
        'image',
        'credit_by',
        'status',
    ];

    /**
     * One Webstory has many contents
     */
    public function contents()
    {
        return $this->hasMany(WebstoryContent::class, 'webstory_id', 'id');
    }
}
