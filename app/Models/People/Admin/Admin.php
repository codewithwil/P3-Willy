<?php

namespace App\Models\People\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table      = 'admins';
    protected $primaryKey = 'adminId';
    protected $fillable   = [
        'user_id', 'foto', 'name', 'telepon',
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    
}
