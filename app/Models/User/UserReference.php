<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $primaryKey = 'userRefId';
    protected $fillable   = [
        'userRefUUID', 'account_id', 'referable_id', 'referable_type', 'device_id', 'status'
    ];

    public function referable(){return $this->morphTo();}
    public function user(){return $this->belongsTo(User::class, 'account_id', 'id');}

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case self::STATUS_ACTIVE:
                return 'Aktif';
            case self::STATUS_INACTIVE:
                return 'TIdak Aktif';
            default:
                return 'Unknown'; 
        }
    }
}
