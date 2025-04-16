<?php

namespace App\Models\People\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table      = 'customers';
    protected $primaryKey = 'customerId';
    protected $fillable   = [
        'user_id', 'foto', 'name', 'telepon', 'address', 'saldo'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
}
