<?php

namespace App\Models;

use App\Enums\ScrollTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BnScroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sorting',
        'type',
        'heading',
        'end_time',
        'created_by',
        'updated_by',
    ];

    // Cast `type` to ScrollTypeEnum
    protected $casts = [
        'end_time' => 'datetime',
        'type' => ScrollTypeEnum::class, // auto-cast enum
    ];

    // Created By relationships with users
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Updated By relationships with users
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Accessor to get the label of the scroll type
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }
}
