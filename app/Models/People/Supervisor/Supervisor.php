<?php

namespace App\Models\People\Supervisor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table      = 'supervisors';
    protected $primaryKey = 'supervisorId';
    protected $fillable   = [
        'user_id', 'foto', 'name', 'telepon',
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    
}
