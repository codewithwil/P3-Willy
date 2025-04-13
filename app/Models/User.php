<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Attendance\Presences\Presences;
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
        'name',
        'email',
        'password',
        'address',
        'phone',
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

    public function loaning(){return $this->hasMany(Loaning::class, 'user_id', 'id');}
    public function service(){return $this->hasMany(Service::class, 'user_id', 'id');}
    public function stockTransac(){return $this->hasMany(StockTransac::class, 'user_id', 'id');}
    public function presences(){return $this->hasOne(Presences::class, 'user_id', 'id');}
    public function employeeShift(){return $this->hasMany(EmployeeShift::class, 'user_id', 'id');}
    public function outCommod(){return $this->hasMany(OutCommodity::class, 'user_id', 'id');}
    public function comeCommod(){return $this->hasMany(ComeCommodity::class, 'user_id', 'id');}
}
