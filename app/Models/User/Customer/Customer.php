<?php

namespace App\Models\User\Customer;

use App\Models\User;
use App\Models\User\UserReference;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $table = 'customers';
    protected $primaryKey  = 'customerId';
    protected $fillable    = [
        'accountId', 'phone', 'address',
    ];

    public function userRef(){return $this->morphOne(UserReference::class, 'referable');}
    public function user()
    {
        return $this->belongsTo(User::class, 'accountId');  
    }
}
