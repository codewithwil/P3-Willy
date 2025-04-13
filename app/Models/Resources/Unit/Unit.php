<?php

namespace App\Models\Resources\Unit;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'units';
    protected $primaryKey = 'unitId';
    protected $fillable   = [
        'name', 'abbreviation', 'status',
    ];
}
