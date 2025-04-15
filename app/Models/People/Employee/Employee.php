<?php

namespace App\Models\People\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table      = 'employees';
    protected $primaryKey = 'employeeId';
    protected $fillable   = [
        'user_id', 'foto', 'name', 'telepon', 'address', 'gender',
        'birthdate', 'hire_date', 'salary', 'status'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
}
