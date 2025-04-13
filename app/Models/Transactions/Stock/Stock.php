<?php

namespace App\Models\Transactions\Stock;

use App\{
    Models\Transactions\Commodity\Commodity
};
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table      = 'stocks';
    protected $primaryKey = 'stockId';
    protected $fillable   = [
        'commodity_id', 'quantity'
    ];

    public function commodity(){return $this->belongsTo(Commodity::class, "commodity_id", "commoditiesId");}
}
