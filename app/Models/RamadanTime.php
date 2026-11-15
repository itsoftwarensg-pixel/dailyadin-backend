<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RamadanTime extends Model
{
    protected $fillable = [
        'title',
        'name',
        'hijri_year',
        'ramadan_start_date',
        'days',
        'division_offsets'
    ];

    protected $casts = [
        'days' => 'array',
        'division_offsets' => 'array',
        'ramadan_start_date' => 'date'
    ];
}
