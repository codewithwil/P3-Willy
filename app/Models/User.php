<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Attendance\Presences\Presences;
use App\Models\People\Admin\Admin;
use App\Models\People\Customers\Customers;
use App\Models\People\Employee\Employee as EmployeeEmployee;
use App\Models\People\Supervisor\Supervisor;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Member\Member;
use Illuminate\{
Database\Eloquent\Factories\HasFactory,
Foundation\Auth\User as Authenticatable,
Notifications\Notifiable,
};

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
/** @use HasFactory<\Database\Factories\UserFactory> */
use HasFactory, Notifiable, HasRoles;

/**
 * The attributes that are mass assignable.
 *
 * @var list<string>
 */
    protected $fillable = [
        'email',
        'password',
        'branch_id',
    ];

/**
 * The attributes that should be hidden for serialization.
 *
    * @var list<string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

/**
 * Get the attributes that should be cast.
 *
 * @return array<string, string>
 */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customers::class, 'user_id');
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasOne(EmployeeEmployee::class, 'user_id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'user_id');
    }
    public function realName()
    {
        if ($this->hasRole('admin') && $this->admin) {
            return $this->admin->name;
        }

        if ($this->hasRole('supervisor') && $this->supervisor) {
            return $this->supervisor->name;
        }

        if ($this->hasRole('employee') && $this->employee) {
            return $this->employee->name;
        }

        if ($this->hasRole('customer') && $this->customer) {
            return $this->customer->name;
        }

        return $this->name; 
    }

}
