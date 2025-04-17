<?php

namespace App\Models\Resources\Branch;

use App\Models\Resources\Company\Company;
use App\Models\Resources\Promo\Promo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'branches';
    protected $primaryKey = 'branchId';
    protected $fillable   = [
        'company_id', 'address', 'email', 'operationalHours', 'phone',
        'ltd', 'lng', 'status' 
    ];

    public function users(){return $this->hasMany(User::class, 'branch_id', 'branchId');}
    public function promo(){return $this->hasMany(Promo::class, 'branch_id', 'branchId');}
    public function company(){return $this->belongsTo(Company::class, 'company_id', 'companyId');}
}
