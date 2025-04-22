<?php

namespace App\Models\Resources\Service;

use App\Models\Resources\Branch\Branch;
use App\Models\Transactions\ServiceTransac\ServiceTransac;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    const STATUS_NOTACTIVE = 0;
    const STATUS_ACTIVE    = 1;
    protected $table       = 'services';
    protected $primaryKey  = 'serviceId';
    protected $fillable    = [
        'branch_id', 'name','pricePerUnit', 'unitType',
        'minQuantity', 'description' ,'status'
    ];

    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}
    public function serviceTransac(){return $this->hasMany(ServiceTransac::class, 'service_id', 'serviceId');}
    
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_NOTACTIVE  => 'Tidak Aktif',
            self::STATUS_ACTIVE     => 'Aktif',
        ];
    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
    
}
