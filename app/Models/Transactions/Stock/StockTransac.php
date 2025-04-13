<?php

namespace App\Models\Transactions\Stock;

use App\{
    Models\Transactions\Commodity\Commodity,
    Models\User
};

use Illuminate\Database\Eloquent\Model;

class StockTransac extends Model
{
    const TYPE_IN         = 1;
    const TYPE_OUT        = 2;
    const TYPE_INUPDATE   = 3;
    const TYPE_OUTUPDATE  = 4;
    const TYPE_RETURNED   = 5;
    protected $table      = 'stock_transacs';
    protected $primaryKey = 'stockTransacId';
    protected $fillable   = [
        'user_id', 'commodity_id', 'type', 'quantity', 'desc'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
    public function commodity(){return $this->belongsTo(Commodity::class, 'commodity_id', 'commoditiesId');}

    public function getTypeTextAttribute(){
        $typeText = [
            self::TYPE_IN            => 'Stok Masuk',
            self::TYPE_OUT           => 'Stok Keluar',
            self::TYPE_INUPDATE      => 'Stok Masuk(Update)',
            self::TYPE_OUTUPDATE     => 'Stok Keluar(Update)',
            self::TYPE_RETURNED      => 'Stok Dikembalikan',
        ];

        return $typeText[$this->type] ?? 'Tipe tidak diketahui';
    }
}
