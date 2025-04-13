<?php

namespace App\Http\Controllers\API\Transactions\Loaning;

use App\{
    Http\Controllers\Controller,
    Models\Transactions\Commodity\Commodity,
    Models\Transactions\Loaning\Loaning,
    Models\Transactions\Stock\StockTransac
};
use App\Models\Resources\Company\Company;
use Exception;
use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\DB,
    Support\Facades\Log,
    Validation\ValidationException
};

class LoaningC extends Controller
{
    public function index(){
        $loanings = Loaning::with(['users', 'commodities'])
                            ->where('statusLoan', '!=', Loaning::STATUS_DIHAPUS)
                            ->get();
        return view('admin.transaction.loanings.index', compact('loanings'));
    }

    public function create()
    {
        $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
            ->where('type', Commodity::TYPE_ALAT)
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->with(['stock', 'category'])
            ->get();
    
        $lastLoaning = Loaning::where('itemCode', 'LIKE', 'PNJ%')
            ->orderBy('itemCode', 'desc')
            ->first();
    
        if ($lastLoaning) {
            $lastNumber = (int)substr($lastLoaning->itemCode, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
    
        $itemCode = 'PNJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
        return view('admin.transaction.loanings.create', compact('commodity', 'itemCode'));
    }

    public function invoice(){
        $loanings = Loaning::with(['users', 'commodities'])
        ->where('statusLoan', '!=', Loaning::STATUS_DIHAPUS)
        ->get();

        $company = Company::first();
        return view('admin.transaction.loanings.invoice', compact('loanings', 'company'));
    }

    public function edit($loaningId){
        $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
            ->where('type', Commodity::TYPE_ALAT)
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->with(['stock'])
            ->get();
        $loanings = Loaning::with(['users', 'commodities'])->findOrFail($loaningId);
        return view('admin.transaction.loanings.update', compact('loanings', 'commodity'));
    }

    public function show($loaningId){
        $loanings = Loaning::with(['users', 'commodities'])
                            ->findOrFail($loaningId);
        return view('admin.transaction.loanings.details', compact('loanings'));
    }

    public function store(Request $req)
    {
        // dd($req->all());
        DB::beginTransaction();  
    
        try {
            $req->merge([
                'commodity_id'  => json_decode($req->input('commodity_id')[0], true),
                'quantity' => json_decode($req->input('quantity')[0], true),
            ]);

            $validated = $req->validate([
                'commodity_id'   => 'required|array',
                'commodity_id.*' => 'exists:commodities,commoditiesId', 
                'quantity'       => 'required|array',
                'quantity.*'     => 'required|integer|min:1',
                'loanDate'       => 'required|date',
                'note'           => 'nullable|string|max:255',
            ]);
        
            $userId = Auth::user()->id;
            $totalQuantity = 0;
        
            $lastLoaning = Loaning::where('itemCode', 'LIKE', 'PNJ%')
                ->orderBy('itemCode', 'desc')
                ->first();
        
            if ($lastLoaning) {
                $lastNumber = (int)substr($lastLoaning->itemCode, 3);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
        
            $itemCode = 'PNJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $newNumber++;  
        
            $loaning = Loaning::create([
                'itemCode' => $itemCode,
                'user_id' => $userId,
                'loanDate' => $validated['loanDate'],
                'note' => $validated['note'] ?? null,
                'statusLoan' => Loaning::STATUS_DIAJUKAN,
            ]);
        
            foreach ($validated['commodity_id'] as $index => $commodityId) {
                $commodity = Commodity::findOrFail($commodityId);
                $quantity = $validated['quantity'][$index];
        
                $currentStock = $commodity->stock->sum('quantity');
                if ($quantity > $currentStock) {
                    return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Stok tidak mencukupi untuk peminjaman barang ' . $commodity->name . '.');
                }
        
                $remainingQuantity = $quantity;
                foreach ($commodity->stock as $stock) {
                    if ($remainingQuantity <= 0) break;
        
                    if ($stock->quantity >= $remainingQuantity) {
                        $stock->update(['quantity' => $stock->quantity - $remainingQuantity]);
                        $remainingQuantity = 0;
                    } else {
                        $remainingQuantity -= $stock->quantity;
                        $stock->update(['quantity' => 0]);
                    }
                }
        
                $loaning->commodities()->attach($commodity->commoditiesId, ['quantity' => $quantity]);
        
                // Record the stock transaction
                // dd([
                //     'user_id' => $userId,
                //     'commodity_id' => $commodity->commoditiesId,
                //     'type' => 2,
                //     'quantity' => $quantity,
                //     'desc' => 'Peminjaman barang dengan kode: ' . $itemCode,
                // ]);
                
                StockTransac::create([
                    'user_id' => $userId,
                    'commodity_id' => $commodity->commoditiesId,
                    'type' => 2,
                    'quantity' => $quantity,
                    'desc' => 'Peminjaman barang dengan kode: ' . $itemCode,
                ]);
        
                $totalQuantity += $quantity;  
            }
    
            DB::commit();
        
            return redirect('/transactions/loanings/')
                ->with('success', $totalQuantity . ' barang berhasil dipinjam.');
        
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $loaningId)
    {
        Log::info('Request Inputs:', $req->all());
    
        try {
            $commodityIds = json_decode($req->input('commodity_id')[0], true);
            $quantities = json_decode($req->input('quantity')[0], true);
            $req->merge([
                'commodity_id' => $commodityIds,
                'quantity' => $quantities,
            ]);
        } catch (\Exception $e) {
            Log::error('Error decoding JSON:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('Invalid data format.');
        }
    
        Log::info('Decoded Commodity IDs:', $commodityIds);
        Log::info('Decoded Quantities:', $quantities);
    
        try {
            $validated = $req->validate([
                'commodity_id'   => 'required|array',
                'commodity_id.*' => 'exists:commodities,commoditiesId',
                'quantity'       => 'required|array',
                'quantity.*'     => 'required|integer|min:1',
                'note'           => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            throw $e;
        }
    
        Log::info('Validated Inputs:', $validated);
    
        $loaning = Loaning::with(['commodities.stock'])->findOrFail($loaningId);
        Log::info('Fetched Loaning Record:', $loaning->toArray());
    
        foreach ($commodityIds as $index => $commodityId) {
            $newQuantity = $quantities[$index] ?? null;
        
            if ($newQuantity === null) {
                Log::error("Missing quantity for Commodity ID: $commodityId");
                continue;
            }
        
            $oldCommodity = $loaning->commodities->firstWhere('commoditiesId', $commodityId);
        
            if ($oldCommodity) {
                $oldQuantity = $oldCommodity->pivot->quantity;
        
                if ($newQuantity != $oldQuantity) {
                    Log::info("Updating Commodity ID: $commodityId, Old Quantity: $oldQuantity, New Quantity: $newQuantity");
                    if ($newQuantity > $oldQuantity) {
                        $difference = $newQuantity - $oldQuantity;
                        $this->adjustStockForExistingCommodity($commodityId, -$difference);
                    } elseif ($newQuantity < $oldQuantity) {
                        $difference = $oldQuantity - $newQuantity;
                        $this->adjustStockForExistingCommodity($commodityId, $difference);
                    }
        
                    $loaning->commodities()->updateExistingPivot($commodityId, ['quantity' => $newQuantity]);
                } else {
                    Log::info("No changes for Commodity ID: $commodityId");
                }
            } else {
                Log::info("Processing new Commodity ID: $commodityId, New Quantity: $newQuantity");
        
                $this->adjustStockForNewCommodity($commodityId, $newQuantity);
        
                $loaning->commodities()->attach($commodityId, ['quantity' => $newQuantity]);
            }
        }
    
        try {
            $loaning->update([
                'note' => $validated['note'] ?? $loaning->note,
            ]);
            Log::info('Loaning Updated Successfully:', $loaning->toArray());
        } catch (\Exception $e) {
            Log::error('Error updating loaning record:', ['error' => $e->getMessage()]);
            throw $e;
        }
    
        return redirect('transactions/loanings')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function return(Request $req, $loaningId)
    {
        try {
            $commodityIds = $req->input('commodity_id'); 
            $quantities = $req->input('quantity'); 
    
            if (empty($commodityIds) || empty($quantities)) {
                throw new \Exception('Invalid or missing commodity data.');
            }
    
            foreach ($commodityIds as $index => $commodityId) {
                if (!isset($quantities[$index])) {
                    throw new \Exception("Missing quantity for Commodity ID: $commodityId");
                }
            }
    
            DB::transaction(function () use ($loaningId, $commodityIds, $quantities) {
                $loan = Loaning::with('commodities')->findOrFail($loaningId);
    
                if ($loan->statusLoan !== Loaning::STATUS_DIPINJAM) {
                    throw new \Exception('Pinjaman tidak valid untuk dikembalikan.');
                }
    
                $loan->update([
                    'returnDate' => now(),
                    'statusLoan' => Loaning::STATUS_DIKEMBALIKAN,
                ]);
    
                foreach ($commodityIds as $index => $commodityId) {
                    $newQuantity = $quantities[$index];
                    $existingCommodity = $loan->commodities->firstWhere('commoditiesId', $commodityId);
    
                    if (!$existingCommodity) {
                        throw new \Exception("Commodity ID: $commodityId not found in loan.");
                    }
    
                    $oldQuantity = $existingCommodity->pivot->quantity;
    
                  if ($newQuantity != $oldQuantity) {
                    $difference = $newQuantity - $oldQuantity;
                    Log::info("Adjusting stock for Commodity ID: $commodityId, Difference: $difference");
                    
                    $this->adjustStockForExistingCommodity($commodityId, $difference);
                } else {
                    Log::info("No quantity change for Commodity ID: $commodityId, confirming return.");
                    $this->adjustStockForExistingCommodity($commodityId, $oldQuantity); 
                }
                
                }
            });
    
            return redirect()->back()->with('success', 'Pinjaman berhasil dikembalikan.');
        } catch (\Exception $e) {
            Log::error('Error returning loan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengembalikan pinjaman.');
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
    
    private function adjustStockForNewCommodity($commodityId, $newQuantity)
    {
        $commodity = Commodity::with('stock')->findOrFail($commodityId);
        $currentStock = $commodity->stock->sum('quantity');
    
        Log::info("Adjusting Stock for Commodity ID: $commodityId, Current Stock: $currentStock, New Quantity: $newQuantity");
    
        if ($newQuantity > $currentStock) {
            Log::error("Stock Insufficient for Commodity ID: $commodityId");
            throw ValidationException::withMessages([
                'quantity' => 'Stok barang tidak mencukupi untuk peminjaman.',
            ]);
        }
    
        $remainingQuantity = $newQuantity;
    
        foreach ($commodity->stock as $stock) {
            if ($remainingQuantity <= 0) break;
    
            if ($stock->quantity >= $remainingQuantity) {
                $stock->update(['quantity' => $stock->quantity - $remainingQuantity]);
    
                // Catat transaksi stok dengan tipe TYPE_OUTUPDATE
                $this->createStockTransaction(
                    $commodityId,
                    StockTransac::TYPE_OUTUPDATE,
                    $remainingQuantity,
                    'Stock updated for new commodity adjustment'
                );
    
                Log::info("Stock updated. Stock ID: {$stock->id}, Remaining Stock: {$stock->quantity}");
                $remainingQuantity = 0;
            } else {
                $this->createStockTransaction(
                    $commodityId,
                    StockTransac::TYPE_OUTUPDATE,
                    $stock->quantity,
                    'Stock depleted for new commodity adjustment'
                );
    
                $remainingQuantity -= $stock->quantity;
                $stock->update(['quantity' => 0]);
                Log::info("Stock depleted. Stock ID: {$stock->id}, Remaining Quantity: $remainingQuantity");
            }
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

    public function delete($loaningId)
    {
        DB::beginTransaction();
        try {
            $loaning = Loaning::with('commodities')->findOrFail($loaningId);
            $userId = Auth::user()->id; 
    
            foreach ($loaning->commodities as $commodity) {
                $quantity = $commodity->pivot->quantity; 
                
                foreach ($commodity->stock as $stock) {
                    $stock->update([
                        'quantity' => $stock->quantity + $quantity
                    ]);
                }
    
                StockTransac::create([
                    'user_id' => $userId,
                    'commodity_id' => $commodity->commoditiesId,
                    'type' => StockTransac::TYPE_RETURNED,
                    'quantity' => $quantity,
                    'desc' => 'Pengembalian stok dari peminjaman dengan kode: ' . $loaning->itemCode,
                ]);
            }
    
            $loaning->commodities()->detach();
    
            $loaning->update([
                'statusLoan' => Loaning::STATUS_DIHAPUS
            ]);
    
            DB::commit();
    
            return redirect('/transactions/loanings/')
                ->with('success', 'Peminjaman berhasil dihapus, stok dikembalikan, dan transaksi tercatat.');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }    
}
