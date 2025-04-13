<?php

namespace App\Models\Resources\Building;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'buildings';
    protected $primaryKey = 'buildingId';
    protected $fillable   = [
    'buildingName', 'status'
    ];

    public function rooms(){return $this->hasMany(Room::class, "building_Id", "buildingId");}
}
