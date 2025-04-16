<?php

namespace App\Models\Resources\Company;

use App\Models\Resources\Files\Files;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table           = 'companies';
    protected $primaryKey      = 'companyId';
    protected $fillable        = [
        'companyId','image','name', 'web'
    ];

    public function branch(){return $this->hasOne(Company::class, 'company_id', 'companyId');}
}

