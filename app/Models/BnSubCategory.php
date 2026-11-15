<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BnSubCategory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'alias',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'description',
        'sorting',
        'photo',
        'status',
    ];


    /**
     * Get the role belongs to the user.
     *
     * @return object
     */
    public function category()
    {
        return $this->belongsTo(BnCategory::class, 'category_id');
    }

    public function contents()
    {
        return $this->hasMany(BnContent::class, 'sub_category_id');
    }
}
