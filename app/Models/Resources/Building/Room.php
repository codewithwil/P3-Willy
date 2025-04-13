<?php

namespace App\Models\Resources\Building;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'rooms';
    protected $primaryKey = 'roomId';
    protected $fillable   = [
        'building_Id', 'roomName','floor', 'roomStatus'
    ];

    public function building(){return $this->belongsTo(Building::class, "building_Id", "buildingId");}

}
