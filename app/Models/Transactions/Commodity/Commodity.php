<?php

namespace App\Models\Transactions\Commodity;

use App\{
    Models\Resources\Building\Room,
    Models\Resources\Category\Category,
    Models\Resources\Unit\Unit
};
use App\Models\Transactions\ComeCommodity\ComeCommodity;
use App\Models\Transactions\Loaning\Loaning;
use App\Models\Transactions\OutCommodity\OutCommodity;
use App\Models\Transactions\Service\Service;
use App\Models\Transactions\Service\ServicesV;
use App\Models\Transactions\Stock\Stock;
use App\Models\Transactions\Stock\StockTransac;
use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    const TYPE_ALAT       = 1;    
    const TYPE_SPAREPART  = 2;    
    protected $table      = 'commodities';
    protected $primaryKey = 'commoditiesId';
    protected $fillable   = [
        'image','room_Id', 'category_id', 'unit_id', 'name', 
        'price','merk','type','desc', 'status'
    ];

    public function room(){return $this->belongsTo(Room::class, "room_Id", "roomId");}
    public function category(){return $this->belongsTo(Category::class, 'category_id', 'categoryId');}
    public function unit(){return $this->belongsTo(Unit::class, 'unit_id', 'unitId');}
    public function stock(){return $this->hasMany(Stock::class, 'commodity_id', 'commoditiesId');}
    public function stockTransac(){
        return $this->hasMany(StockTransac::class, 'commodity_id', 'commoditiesId');
    }
    
    public function loanings()
    {
    return $this->belongsToMany(Loaning::class, 'loaning_commodities', 'commodity_id', 'loaning_id')
                ->withPivot('quantity');
    }

    public function Services()
    {
    return $this->belongsToMany(Service::class, 'service_commodities', 'commodity_id', 'service_id')
                ->withPivot('quantity');
    }

    public function OutCommodity()
    {
    return $this->belongsToMany(OutCommodity::class, 'out_commodity_pivots', 'commodity_id', 'outCom_id')
                ->withPivot('quantity');
    }

    public function ComeCommodity()
    {
    return $this->belongsToMany(ComeCommodity::class, 'come_commodity_pivots', 'commodity_id', 'comeCom_id')
                ->withPivot('quantity');
    }

    public function ServiceV()
    {
    return $this->belongsToMany(ServicesV::class, 'service_vehicle_commodities', 'commodity_id', 'serv_id')
                ->withPivot('quantity');
    }

    public function getStatusTextAttribute(){
        return $this->status == self::STATUS_ACTIVE ? 'Aktif' : 'Tidak aktif';
    }

    public function getTypeTextAttribute(){
        $typeText = [
            self::TYPE_ALAT      => 'Alat',
            self::TYPE_SPAREPART => 'Sparepart',
        ];

        return $typeText[$this->type] ?? 'Status tidak diketahui';
    }

}
