<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdDivision extends Model
{
    protected static function boot()
    {
        parent::boot();

        // Prevent creating
        static::creating(function ($model) {
            throw new \Exception('Creating new division is not allowed.');
        });

        // Prevent deleting
        static::deleting(function ($model) {
            throw new \Exception('Deleting division is not allowed.');
        });
    }

    public function districts()
    {
        return $this->hasMany(BdDistrict::class, 'division_id');
    }

    /**
     * Relationship: A division has many contents through districts
     * Position	Argument	Description
     * 1	BnContent::class	The Final Model: The data you actually want to retrieve.
     * 2	BdDistrict::class	The Intermediate Model: The "bridge" that connects the two.
     * 3	'division_id'	Foreign Key on Intermediate: The column in bd_districts that points to the current model.
     * 4	'district_id'	Foreign Key on Final: The column in bn_contents that points to the intermediate model.
     * 5	'id'	Local Key: The primary key of the current model (bn_divisions).
     * 6	'id'	Local Key of Intermediate: The primary key of bd_districts.
     */
    public function contents()
    {
        return $this->hasManyThrough(BnContent::class, BdDistrict::class, 'division_id', 'district_id', 'id', 'id');
    }
}
