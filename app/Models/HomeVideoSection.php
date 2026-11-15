<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeVideoSection extends Model
{
    protected $fillable = [
        'heading',
        'embed_code',
        'type',
        'is_live',
        'status',
    ];
}
