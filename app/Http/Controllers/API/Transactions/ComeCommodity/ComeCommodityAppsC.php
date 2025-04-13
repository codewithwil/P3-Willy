<?php

namespace App\Http\Controllers\API\Transactions\ComeCommodity;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Transactions\ComeCommodity\ComeCommodity;
use App\Models\Transactions\Commodity\Commodity;
use App\Models\Transactions\Stock\StockTransac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ComeCommodityAppsC extends Controller
{
    public function index(){
        $comeCommod = ComeCommodity::with(['users','commodities' , 'supplier'])
                                    ->where('statusComeCom', '!=', ComeCommodity::STATUS_DIHAPUS)
                                    ->get();
        return view('admin.transaction.comeCommodApps.index', compact('comeCommod'));
    }

    public function invoice(){
        $comeCommod = ComeCommodity::with(['users', 'commodities', 'supplier'])
        ->where('statusComeCom', '!=', ComeCommodity::STATUS_DIHAPUS)
        ->get();
        $company = Company::first();
        return view('admin.transaction.comeCommodApps.invoice', compact('comeCommod', 'company'));
    }

    public function update(Request $request, $comeComdId)
    {
        Log::info('Request Inputs:', $request->all());
    
        $validated = $request->validate([
            'statusComeCom' => 'required|integer|in:2,3', // 2 = DITOLAK, 3 = SUKSES
        ]);
    
        $loaning = ComeCommodity::with(['commodities.stock'])->findOrFail($comeComdId);
    
        try {
            // If status is accepted, increase the stock
            if ($validated['statusComeCom'] == ComeCommodity::STATUS_SUKSES) {
                foreach ($loaning->commodities as $commodity) {
                    $quantity = $commodity->pivot->quantity;
                    $this->adjustStockForExistingCommodity($commodity->commoditiesId, $quantity); // Increase stock
                }
                Log::info('Stock updated for accepted loaning ID: ' . $comeComdId);
            }
    
            // Update the loaning status
            $loaning->update([
                'statusComeCom' => $validated['statusComeCom'],
            ]);
    
            $message = $validated['statusComeCom'] == ComeCommodity::STATUS_DITOLAK 
                ? 'Barang Masuk berhasil ditolak dan stok telah ditolak.'
                : 'Barang Masuk berhasil diperbarui ke status sukses, stok telah diperbarui.';
    
            return redirect('transactions/comeCommodApps')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error updating loaning status:', ['error' => $e->getMessage()]);
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


    private function adjustStockForExistingCommodity($commodityId, $quantityChange)
    {
        $commodity = Commodity::with('stock')->findOrFail($commodityId);
    
        Log::info("Adjusting Stock for Existing Commodity ID: $commodityId, Quantity Change: $quantityChange");
    

        if ($quantityChange > 0) {
            foreach ($commodity->stock as $stock) {
                if ($quantityChange <= 0) break;
    
                $updatedQuantity = $stock->quantity + $quantityChange;
                $stock->update(['quantity' => $updatedQuantity]);
    
                // Record stock transaction (IN)
                $this->createStockTransaction($commodityId, StockTransac::TYPE_IN, $quantityChange, 'Stock ditambahkan ke inventory');
    
                Log::info("Stock added. Stock ID: {$stock->id}, Updated Stock: {$stock->quantity}");
                $quantityChange = 0;
            }
        } elseif ($quantityChange < 0) {
            // If quantityChange is negative, we decrease stock (same logic as rejection)
            $remainingQuantity = abs($quantityChange);
    
            foreach ($commodity->stock as $stock) {
                if ($remainingQuantity <= 0) break;
    
                if ($stock->quantity >= $remainingQuantity) {
                    $stock->update(['quantity' => $stock->quantity - $remainingQuantity]);
    
                    // Record stock transaction (OUT)
                    $this->createStockTransaction($commodityId, StockTransac::TYPE_OUT, $remainingQuantity, 'Stok dikeluarkan dari inventory');
    
                    Log::info("Stock updated. Stock ID: {$stock->id}, Remaining Stock: {$stock->quantity}");
                    $remainingQuantity = 0;
                } else {
                    $this->createStockTransaction($commodityId, StockTransac::TYPE_OUT, $stock->quantity, 'Stok dikeluarkan dari inventory');
    
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
