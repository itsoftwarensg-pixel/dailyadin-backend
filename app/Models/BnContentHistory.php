<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BnContentHistory extends Model
{
    protected $fillable = ['bn_content_id', 'user_id', 'action', 'changes'];

    protected $casts = [
        'changes' => 'array',
    ];

    public function bnContent()
    {
        return $this->belongsTo(BnContent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
