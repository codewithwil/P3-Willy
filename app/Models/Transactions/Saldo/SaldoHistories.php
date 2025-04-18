<?php

namespace App\Models\Transactions\Saldo;

use App\Models\People\Customers\Customers;
use Illuminate\Database\Eloquent\Model;

class SaldoHistories extends Model
{
    const TYPE_DEPOSIT     = 0;
    const TYPE_WITHDRAW    = 1;
    protected $table       = 'saldo_histories';
    protected $primaryKey  = 'saldoHistId';
    protected $fillable    = [
        'customer_id', 'amount', 'type', 'description', 
    ];

    public function custome(){return $this->belongsTo(Customers::class, 'customer_id', 'customerId');}
    
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::TYPE_DEPOSIT  => 'Deposit',
            self::TYPE_WITHDRAW => 'Penarikan',
        ];
    
        return $labels[$this->type] ?? 'Tidak Diketahui';
    }
    
}
