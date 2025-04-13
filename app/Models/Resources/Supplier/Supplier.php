<?php

namespace App\Models\Resources\Supplier;

use App\Models\Transactions\ComeCommodity\ComeCommodity;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'suppliers';
    protected $primaryKey = 'supplierId';
    protected $fillable   = [
        'name', 'email', 'phone', 'address', 'status'
    ];

    public function ComeCommod(){return $this->hasMany(ComeCommodity::class, 'supplier_id', 'supplierId');}
}
