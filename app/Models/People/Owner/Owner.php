<?php

namespace App\Models\People\Owner;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table      = 'owners';
    protected $primaryKey = 'ownerId';
    protected $fillable   = [
        'user_id', 'foto', 'name', 'telepon', 'address'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    
}
