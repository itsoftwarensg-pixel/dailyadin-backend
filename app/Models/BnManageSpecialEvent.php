<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BnManageSpecialEvent extends Model
{
    protected $table = 'bn_manage_special_event';

    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'event_url',
        'news_ids',
        'active_status',
    ];
}
