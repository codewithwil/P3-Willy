<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Attendance\Presences\Presences;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\ManagementShift\EmployeeShift;
use App\Models\Transactions\ComeCommodity\ComeCommodity;
use App\Models\Transactions\Loaning\Loaning;
use App\Models\Transactions\OutCommodity\OutCommodity;
use App\Models\Transactions\Service\Service;
use App\Models\Transactions\Stock\StockTransac;
use App\Models\User\Employee\Employee;
use App\Models\User\UserReference;
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

}
