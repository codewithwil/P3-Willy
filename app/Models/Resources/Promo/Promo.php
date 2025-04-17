<?php

namespace App\Models\Resources\Promo;

use App\Models\Resources\Branch\Branch;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    
    const TARGET_MEMBER    = 1;
    const TARGET_BRANCH    = 2;
    const TYPE_PERCENT     = 1;
    const TYPE_NOMINAL     = 2;
    const STATUS_NOTACTIVE = 0;
    const STATUS_ACTIVE    = 1;
    protected $table       = 'promos';
    protected $primaryKey  = 'promoId';
    protected $fillable    = [
        'branch_id', 'promoCode', 'promoName', 'target_audience','description', 
        'startDate', 'endDate', 'typePromo', 'amountPromo', 'status'
    ];


    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_NOTACTIVE  => 'Tidak Aktif',
            self::STATUS_ACTIVE     => 'Aktif',
        ];
    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    public function getTypeLabelAttribute(){
        $labels = [
            self::TYPE_PERCENT  => 'Persentase',
            self::TYPE_NOMINAL  => 'Nominal',
        ];
    
        return $labels[$this->typePromo] ?? 'Tidak Diketahui';
    }
    
    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}
    
}
