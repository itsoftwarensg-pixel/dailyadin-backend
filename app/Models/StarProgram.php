<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StarProgram extends Model
{
    protected $fillable = [
        'identity',
        'details',
        'quote',
        'photo',
        'video_link',
        'status',
    ];
}
