<?php

namespace App\Models\User\Employee;

use App\{
    Models\User\UserReference,
};
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    const STATUS_RESIGN    = 0;
    const STATUS_ACTIVE    = 1;
    const STATUS_DIPECAT   = 2;
    const STATUS_MENINGGAL = 3;
    protected $primaryKey  = 'employeeId';
    protected $fillable    = [
        'accountId', 'phone', 'address', 'basicSallary', 
        'hireDate', 'resignDate', 'statusEmp',
    ];

    public function userRef(){return $this->morphOne(UserReference::class, 'referable');}
    public function user()
    {
        return $this->belongsTo(User::class, 'accountId', 'id');  
    }
}
