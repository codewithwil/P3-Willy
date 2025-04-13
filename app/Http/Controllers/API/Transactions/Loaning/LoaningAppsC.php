<?php

namespace App\Http\Controllers\API\Transactions\Loaning;

use App\{
    Http\Controllers\Controller,
    Models\Transactions\Loaning\Loaning,
    Models\Transactions\Commodity\Commodity,
    Models\Resources\Company\Company,
    Models\Transactions\Stock\StockTransac,
};
use Illuminate\{
    Http\Request,
    Validation\ValidationException,
    Support\Facades\Log,
    Support\Facades\Auth
};


class LoaningAppsC extends Controller
{
    public function index(){
        $loanings = Loaning::with(['users', 'commodities'])
        ->where('statusLoan', '!=', Loaning::STATUS_DIHAPUS)
        ->get();
        return view('admin.transaction.loaningApps.index', compact('loanings'));
    }

    public function invoice(){
        $loanings = Loaning::with(['users', 'commodities'])
        ->where('statusLoan', '!=', Loaning::STATUS_DIHAPUS)
        ->get();
        $company = Company::first();
        return view('admin.transaction.loaningApps.invoice', compact('loanings', 'company'));
    }

    public function update(Request $request, $loaningId)
    {
        Log::info('Request Inputs:', $request->all());
    
        // Validate the statusLoan input
        $validated = $request->validate([
            'statusLoan' => 'required|integer|in:2,3', 
        ]);
    
        $loaning = Loaning::with(['commodities.stock'])->findOrFail($loaningId);
    
        try {
            if ($validated['statusLoan'] == Loaning::STATUS_DITOLAK) {
                foreach ($loaning->commodities as $commodity) {
                    $quantity = $commodity->pivot->quantity;
                    $this->adjustStockForExistingCommodity($commodity->commoditiesId, $quantity);
                }
                Log::info('Stock returned for rejected loaning ID: ' . $loaningId);
            }
    
            // Update the loaning status
            $loaning->update([
                'statusLoan' => $validated['statusLoan'],
            ]);
    
            $message = $validated['statusLoan'] == Loaning::STATUS_DITOLAK 
                ? 'Peminjaman berhasil ditolak dan stok telah dikembalikan.'
                : 'Peminjaman berhasil diperbarui ke status Dipinjam.';
    
            return redirect('transactions/loaningsApps')->with('success', $message);
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
    
                // Catat transaksi stok (Returned)
                $this->createStockTransaction($commodityId, StockTransac::TYPE_RETURNED, $quantityChange, 'Stock returned to inventory');
    
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
                    $this->createStockTransaction($commodityId, StockTransac::TYPE_OUT, $remainingQuantity, 'Stock taken from inventory');
    
                    Log::info("Stock updated. Stock ID: {$stock->id}, Remaining Stock: {$stock->quantity}");
                    $remainingQuantity = 0;
                } else {
                    $this->createStockTransaction($commodityId, StockTransac::TYPE_OUT, $stock->quantity, 'Stock depleted from inventory');
    
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
