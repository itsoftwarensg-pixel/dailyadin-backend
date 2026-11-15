<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdDistrict extends Model
{
    protected static function boot()
    {
        parent::boot();

        // Prevent creating
        static::creating(function ($model) {
            throw new \Exception("Creating new district is not allowed.");
        });

        // Prevent deleting
        static::deleting(function ($model) {
            throw new \Exception("Deleting district is not allowed.");
        });
    }

    public function division()
    {
        return $this->belongsTo(BdDivision::class, 'division_id');
    }

    public function upazilas()
    {
        return $this->hasMany(BdUpazila::class, 'district_id');
    }

    /**
     * Relationship: A district has many contents
     */
    public function contents()
    {
        return $this->hasMany(BnContent::class, 'district_id');
    }
}
