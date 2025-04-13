<?php

namespace App\Http\Controllers\API\Transactions\Service;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Transactions\Commodity\Commodity;
use App\Models\Transactions\Service\Service;
use App\Models\Transactions\Stock\StockTransac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ServiceAppsC extends Controller
{
    public function index(){
        $services = Service::with(['users', 'commodities'])
        ->where('serviceStatus', '!=', Service::STATUS_DIHAPUS)
        ->get();
        return view('admin.transaction.serviceApps.index', compact('services'));
    }

    public function invoice(){
        $services = Service::with(['users', 'commodities'])
        ->where('serviceStatus', '!=', Service::STATUS_DIHAPUS)
        ->get();
        $company = Company::first();
        return view('admin.transaction.serviceApps.invoice', compact('services', 'company'));
    }

    public function update(Request $request, $loaningId)
    {
        Log::info('Request Inputs:', $request->all());
    
        $validated = $request->validate([
            'serviceStatus' => 'required|integer|in:2,3', 
        ]);
    
        $service = Service::with(['commodities.stock'])->findOrFail($loaningId);
    
        try {
            if ($validated['serviceStatus'] == Service::STATUS_DITOLAK) {
                foreach ($service->commodities as $commodity) {
                    $quantity = $commodity->pivot->quantity;
                    $this->adjustStockForExistingCommodity($commodity->commoditiesId, $quantity, $service);
                }
                Log::info('Stock returned for rejected service ID: ' . $loaningId);
            }
    
            $service->update([
                'serviceStatus'     => $validated['serviceStatus'],
            ]);
    
            $message = $validated['serviceStatus'] == Service::STATUS_DITOLAK 
                ? 'Servis Barang berhasil ditolak dan stok telah dikembalikan.'
                : 'Servis Barang berhasil diperbarui ke status Dipinjam.';
    
            return redirect('transactions/serviceApps')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error updating service status:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('Gagal memperbarui status.');
        }
    }

    private function createStockTransaction($commodityId, $type, $quantity, $desc)
    {
        try {
            StockTransac::create([
                'user_id'      => Auth::user()->id, 
                'commodity_id' => $commodityId,
                'type'         => $type,
                'quantity'     => $quantity,
                'desc'         => $desc,
            ]);

            Log::info("Stock transaction recorded. Commodity ID: $commodityId, Type: $type, Quantity: $quantity, Description: $desc");
        } catch (\Exception $e) {
            Log::error('Failed to record stock transaction:', ['error' => $e->getMessage()]);
            throw new \Exception('Gagal mencatat transaksi stok.');
        }
    }


    private function adjustStockForExistingCommodity($commodityId, $quantityChange, $service)
    {
        $commodity = Commodity::with('stock')->findOrFail($commodityId);
    
        Log::info("Adjusting Stock for Existing Commodity ID: $commodityId, Quantity Change: $quantityChange");
    
        if ($quantityChange > 0) {
            foreach ($commodity->stock as $stock) {
                if ($quantityChange <= 0) break;
    
                $updatedQuantity = $stock->quantity + $quantityChange;
                $stock->update(['quantity' => $updatedQuantity]);
    
                // Catat transaksi stok (Returned)
                $this->createStockTransaction($commodityId, StockTransac::TYPE_RETURNED, $quantityChange, 'Pengembalian Barang Dari servis', $service->serviceCode);
    
                Log::info("Stock returned. Stock ID: {$stock->id}, Updated Stock: {$stock->quantity}");
                $quantityChange = 0;
            }
        } elseif ($quantityChange < 0) {
            $remainingQuantity = abs($quantityChange);
    
            foreach ($commodity->stock as $stock) {
                if ($remainingQuantity <= 0) break;
    
                if ($stock->quantity >= $remainingQuantity) {
                    $stock->update(['quantity' => $stock->quantity - $remainingQuantity]);
    
                    // Catat transaksi stok (Out)
                    $this->createStockTransaction($commodityId, StockTransac::TYPE_OUT, $remainingQuantity, 'Stok diambil dari persediaan');
    
                    Log::info("Stock updated. Stock ID: {$stock->id}, Remaining Stock: {$stock->quantity}");
                    $remainingQuantity = 0;
                } else {
                    $this->createStockTransaction($commodityId, StockTransac::TYPE_OUT, $stock->quantity, 'Stok habis dari persediaan');
    
                    $remainingQuantity -= $stock->quantity;
                    $stock->update(['quantity' => 0]);
                    Log::info("Stock depleted. Stock ID: {$stock->id}, Remaining Quantity: $remainingQuantity");
                }
            }
    
            if ($remainingQuantity > 0) {
                Log::error("Insufficient stock for Commodity ID: $commodityId");
                throw ValidationException::withMessages([
                    'quantity' => 'Stok barang tidak mencukupi untuk pembaruan.',
                ]);
            }
        }
    }
    
}
