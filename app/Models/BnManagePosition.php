<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BnManagePosition extends Model
{
    protected $fillable = [
        'position_type',
        'position_type_id',
        'max_content',
        'ids',
    ];
}
