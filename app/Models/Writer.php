<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Writer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'alias',
        'title',
        'description',
        'photo',
        'status'
    ];

    /**
     * Relationship: A writer has many contents
     */
    public function contents()
    {
        return $this->hasMany(BnContent::class, 'writer_id');
    }
}
