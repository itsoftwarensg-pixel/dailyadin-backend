<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BnTotalHit extends Model
{
    protected $table = 'bn_total_hits';
    protected $primaryKey = 'bn_content_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['bn_content_id', 'total_hits'];

    public function content()
    {
        return $this->belongsTo(BnContent::class, 'bn_content_id');
    }
}
