<?php

namespace App\Models\Transactions\ServiceTransac;

use App\{
    Models\People\Customers\Customers,
    Models\Resources\Branch\Branch,
    Models\Resources\Service\Service,
    Models\User,
};

use Illuminate\Database\Eloquent\Model;

class ServiceTransac extends Model
{
    const STATUS_DIBATALKAN = 0;
    const STATUS_PENDING    = 1;
    const STATUS_PROSES     = 2;
    const STATUS_DIANTAR    = 3;
    const STATUS_DIKIRIM    = 4;
    const STATUS_SELESAI    = 5;
    const DO_DELIVER        = 1;
    const DO_DROPOFF        = 2;
    const PAYM_CASH         = 1;
    const PAYM_SALDO        = 2;
    protected $table        = 'service_transacs';
    protected $primaryKey   = 'serviceTransId';
    protected $fillable     = [
        'branch_id', 'customer_id','weight', 'note', 'service_id',
        'paymentMethod', 'deliverOption' , 'postage' , 'total' ,'status'
    ];

    public function branch(){return $this->belongsTo(Branch::class, 'branch_id', 'branchId');}
    public function service(){return $this->belongsTo(Service::class, 'service_id', 'serviceId');}
    public function customer(){return $this->belongsTo(Customers::class, 'customer_id', 'customerId');}
    
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_DIBATALKAN => 'DiBatalkan',
            self::STATUS_PENDING    => 'Pending',
            self::STATUS_PROSES     => 'Diproses',
            self::STATUS_DIANTAR    => 'Diantar',
            self::STATUS_DIKIRIM    => 'Dikirim',
            self::STATUS_SELESAI    => 'Selesai',
        ];
    
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    public function getPaymentLabelAttribute()
    {
        $labels = [
            self::PAYM_CASH  => 'Cash',
            self::PAYM_SALDO => 'Virtual akun',
        ];
    
        return $labels[$this->paymentMethod] ?? 'Tidak Diketahui';
    }

    public function getDeliverOpLabelAttribute()
    {
        $labels = [
            self::DO_DELIVER => 'Antar Jemput',
            self::DO_DROPOFF => 'Antar Sendiri',
        ];
    
        return $labels[$this->deliverOption] ?? 'Tidak Diketahui';
    }
}
