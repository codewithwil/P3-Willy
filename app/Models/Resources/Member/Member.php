<?php

namespace App\Models\Resources\Member;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    const STATUS_NOTACTIVE = 0;
    const STATUS_ACTIVE    = 1;
    protected $table       = 'members';
    protected $primaryKey  = 'memberId';
    protected $fillable    = [
        'user_id', 'dateJoin','status'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_NOTACTIVE  => 'Tidak Aktif',
            self::STATUS_ACTIVE     => 'Aktif',
        ];
    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
    
}
