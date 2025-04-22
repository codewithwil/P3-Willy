<?php
namespace App\Http\Controllers\API\Transactions\ServiceTransac;

use App\{
    Http\Controllers\Controller,
    Models\Transactions\ServiceTransac\ServiceTransac,
};
use App\Models\People\Customers\Customers;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Service\Service;
use App\Models\Transactions\Saldo\SaldoHistories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceTransacC extends Controller
{

    public function index(){
        $order = ServiceTransac::with(['branch', 'service', 'customer'])->get();
        return view('admin.transactions.order.index', compact('order'));
    }
    
    public function byId($customerId)
    {
        $orders = ServiceTransac::where('customer_id', $customerId)->latest()->get(); 
    
        return view('front.order.me', compact('orders'));
    }
    

    public function cash(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'customerId'     => 'required|exists:customers,customerId',
                'branchId'       => 'required|exists:branches,branchId',
                'serviceId'      => 'required|exists:services,serviceId',
                'berat'          => 'required|min:0.1',
                'catatan'        => 'nullable|string|max:255',
                'deliveryOption' => 'required|in:1,2',
                'ongkir'         => 'required|min:0',
                'paymentMethod'  => 'required|in:1,2',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $customer = Customers::find($req->customerId);
            $branch = Branch::find($req->branchId);
            $service = Service::find($req->serviceId);
    
            if (!$service) {
                return redirect()->back()->with('error', 'Layanan tidak ditemukan!');
            }
    
            $promos = $this->getApplicablePromos($branch, $customer);
    
            $berat = $req->berat;
            $ongkir = $req->ongkir;
            $hargaPerKg = $service->pricePerUnit;
            $total = ($berat * $hargaPerKg) + $ongkir;
    
            $totalDiscount = 0;
            foreach ($promos as $promo) {
                $discount = $this->applyPromo($promo, $total);
                $totalDiscount += $discount;
                \Log::info('Applied Promo:', ['Promo ID' => $promo->promoId, 'Discount' => $discount]);
            }
    
            $finalTotal = max($total - $totalDiscount, 0);
            \Log::info('Final Total After Discount:', [$finalTotal]);
    
            $order = ServiceTransac::create([
                'customer_id'   => $req->customerId,
                'branch_id'     => $req->branchId,
                'service_id'    => $req->serviceId,
                'weight'        => $berat,
                'note'          => $req->catatan,
                'deliverOption' => $req->deliveryOption,
                'postage'       => $ongkir,
                'total'         => $finalTotal,  
                'paymentMethod' => $req->paymentMethod,
                'status'        => ServiceTransac::STATUS_PENDING,
            ]);
    
            DB::commit();
    
            return redirect('/order')->with('success', "Order berhasil ditambahkan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    

    public function saldo(Request $req)
    {  
        // dd($req->all());
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'customerId'     => 'required|exists:customers,customerId',
                'branchId'       => 'required|exists:branches,branchId',
                'serviceId'      => 'required|exists:services,serviceId',
                'berat'          => 'required|min:0.1',
                'catatan'        => 'nullable|string|max:255',
                'deliveryOption' => 'required|in:1,2',
                'ongkir'         => 'required|min:0',
                'paymentMethod'  => 'required|in:1,2',
            ]);
            
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $service = Service::find($req->serviceId);
            if (!$service) {
                return redirect()->back()->with('error', 'Layanan tidak ditemukan!');
            }
    
            $customer = Customers::find($req->customerId);
            $branch = Branch::find($req->branchId);

            $promos = $this->getApplicablePromos($branch, $customer);
            // Calculate the price
            $hargaPerKg = $service->pricePerUnit;
            $berat = $req->berat;
            $ongkir = $req->ongkir;
            $total = ($berat * $hargaPerKg) + $ongkir;
    
            $totalDiscount = 0;
            foreach ($promos as $promo) {
                $discount = $this->applyPromo($promo, $total);
                $totalDiscount += $discount;
                \Log::info('Applied Promo:', ['Promo ID' => $promo->promoId, 'Discount' => $discount]);
            }
            
            \Log::info('Total Discount Applied: ', [$totalDiscount]);
    
            $finalTotal = max($total - $totalDiscount, 0);
    
            \log::info('Final Total: ', [$finalTotal]);
    
            if ($customer->saldo < $finalTotal) {
                return redirect()->back()->with('error', 'Saldo Anda tidak cukup untuk melakukan transaksi!');
            }
    
            $customer->saldo -= $finalTotal;
            $customer->save();
    
            SaldoHistories::create([
                'customer_id' => $req->customerId,
                'amount'      => $finalTotal,
                'type'        => SaldoHistories::TYPE_WITHDRAW,
                'description' => 'Pembayaran untuk layanan ' . $req->serviceId,
            ]);
    
            $order = ServiceTransac::create([
                'customer_id'   => $req->customerId,
                'branch_id'     => $req->branchId,
                'service_id'    => $req->serviceId,
                'weight'        => $berat,
                'note'          => $req->catatan,
                'deliverOption' => $req->deliveryOption,
                'postage'       => $ongkir,
                'total'         => $finalTotal,
                'paymentMethod' => $req->paymentMethod,
                'status'        => ServiceTransac::STATUS_PENDING,
            ]);
    
            DB::commit();
    
            return redirect('/order')->with('success', "Order $order->serviceTransId berhasil ditambahkan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    private function getApplicablePromos($branch, $customer)
    {
        $promos = [];
    
        \Log::info('Branch Promo:', [$branch->promo ?? 'No promo']);
        \Log::info('Customer Promo:', [$customer->promo ?? 'No promo']);
    
        if ($branch && $branch->promo) {
            foreach ($branch->promo as $promo) {
                $promos[] = $promo;  
            }
        }
    
        if ($customer && $customer->promo) {
            foreach ($customer->promo as $promo) {
                $promos[] = $promo;  
            }
        }
    
        return $promos;
    }
    
    
    private function applyPromo($promo, $total)
    {
        if (!$promo || !isset($promo->typePromo) || !isset($promo->amountPromo)) {
            \Log::warning('Invalid promo data or missing fields', [$promo]);
            return 0;
        }
    
        $amountPromo = floatval($promo->amountPromo);
    
        if ($promo->typePromo == 1) {
            $discount = $total * ($amountPromo / 100);
            \Log::info('Applying percentage promo:', ['Discount' => $discount]);
            return $discount;
        }
    
        $discount = $amountPromo;
        \Log::info('Applying fixed amount promo:', ['Discount' => $discount]);
        return $discount;
    }
    
    
    
}
