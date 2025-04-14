<?php

namespace App\Models\Resources\Company;

use App\Models\Resources\Files\Files;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table           = 'companies';
    protected $primaryKey      = 'companyId';
    protected $fillable        = [
        'branch_id','companyId','image','name', 'email', 'phone', 'address'
    ];


}

