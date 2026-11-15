<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdUpazila extends Model
{
    protected static function boot()
    {
        parent::boot();

        // Prevent deleting
        static::deleting(function ($model) {
            throw new \Exception("Deleting upazlia is not allowed.");
        });
    }

    public function district()
    {
        return $this->belongsTo(BdDistrict::class, 'district_id');
    }

    /**
     * Relationship: An upazila belongs to a division through district
     */
    public function division()
    {
        return $this->hasOneThrough(BdDivision::class, BdDistrict::class, 'id', 'id', 'district_id', 'division_id');
    }

    /**
     * Relationship: An upazila has many contents
     */
    public function contents()
    {
        return $this->hasMany(BnContent::class, 'upazila_id');
    }
}
