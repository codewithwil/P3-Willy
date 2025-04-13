<?php

namespace App\Http\Controllers\API\Transactions\OutCommodity;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Transactions\Commodity\Commodity,
    Models\Transactions\OutCommodity\OutCommodity,
    Models\Transactions\Stock\StockTransac,
};

use Exception;
use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\DB,
    Support\Facades\Log,
    Validation\ValidationException,
};

class OutCommodityC extends Controller
{
    public function index(){
        $outCommod = OutCommodity::with(['users', 'commodities'])
        ->where('outStatus', '!=', OutCommodity::STATUS_DIHAPUS)
        ->get();
        return view('admin.transaction.outCommod.index', compact('outCommod')); 
    }

    public function show($outComId){
        $outCommod = OutCommodity::with(['users', 'commodities'])->findOrFail($outComId);
        
        $outCommod->outDetails = json_decode($outCommod->outDetails, true);

        return view('admin.transaction.outCommod.details', compact('outCommod')); 
     }
 
     public function create()
     {
         $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
             ->whereHas('stock', function ($query) {
                 $query->where('quantity', '>', 0);
             })
             ->with(['stock', 'category'])
             ->get();
     
             $lastCode = OutCommodity::where('outCode', 'LIKE', 'BRGK%')
             ->orderByRaw("CAST(SUBSTRING(outCode, 5, LENGTH(outCode)) AS UNSIGNED) DESC")
             ->first();
         
     
         if ($lastCode) {
             $lastNumber = (int)substr($lastCode->outCode, 5);
             $newNumber = $lastNumber + 1;
         } else {
             $newNumber = 1;
         }
     
         $outCode = 'BRGK' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
     
         return view('admin.transaction.outCommod.create', compact('commodity', 'outCode'));
     }
 
     public function invoice(){
         $outCommod = OutCommodity::with(['users', 'commodities'])
         ->where('outStatus', '!=', OutCommodity::STATUS_DIHAPUS)
         ->get();
 
         $company = Company::first();
         return view('admin.transaction.outCommod.invoice', compact('outCommod', 'company'));
     }
 

     public function edit($outComId){
         $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
             ->whereHas('stock', function ($query) {
                 $query->where('quantity', '>', 0);
             })
             ->with(['stock'])
             ->get();
         $outCommod = OutCommodity::with(['users', 'commodities'])->findOrFail($outComId);
         $outCommod->outDetails = json_decode($outCommod->outDetails, true);
         return view('admin.transaction.outCommod.update', compact('outCommod', 'commodity'));
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
                 'dateOut'        => 'required|date',
                 'note'           => 'nullable|string|max:255',
                 'outDetails'     => 'nullable|array',
                 'type'           => 'required|integer|digits_between:0,3',
             ]);
         
             $userId = Auth::user()->id;
             $totalQuantity = 0;
         
             $lastCode = OutCommodity::where('outCode', 'LIKE', 'BRGK%')
                 ->orderBy('outCode', 'desc')
                 ->first();
         
             if ($lastCode) {
                 $lastNumber = (int)substr($lastCode->outCode, 5);
                 $newNumber = $lastNumber + 1;
             } else {
                 $newNumber = 1;
             }
         
             $outCode = 'BRGK' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
             $newNumber++;  
         
             $outComm = OutCommodity::create([
                 'user_id'        => $userId,
                 'outCode'        => $outCode,
                 'dateOut'        => $validated['dateOut'],
                 'type'           => $validated['type'],
                 'note'           => $validated['note'] ?? null,
                 'outDetails'     => json_encode($validated['outDetails'] ?? null),
                 'outStatus'      => OutCommodity::STATUS_DIAJUKAN,
             ]);
         
             foreach ($validated['commodity_id'] as $index => $commodityId) {
                 $commodity = Commodity::findOrFail($commodityId);
                 $quantity = $validated['quantity'][$index];
         
                 $currentStock = $commodity->stock->sum('quantity');
                 if ($quantity > $currentStock) {
                     return redirect()
                     ->back()
                     ->withInput()
                     ->with('error', 'Stok tidak mencukupi untuk servis barang ' . $commodity->name . '.');
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
         
                 $outComm->commodities()->attach($commodity->commoditiesId, ['quantity' => $quantity]);
                 
                 StockTransac::create([
                     'user_id'      => $userId,
                     'commodity_id' => $commodity->commoditiesId,
                     'type'         => 2,
                     'quantity'     => $quantity,
                     'desc'         => 'barang keluar dengan kode: ' . $outCode,
                 ]);
         
                 $totalQuantity += $quantity;  
             }
     
             DB::commit();
         
             return redirect('/transactions/outCommod/')
                 ->with('success', $totalQuantity . ' barang keluar berhasil ditambahkan.');
         
         } catch (Exception $e) {
             DB::rollBack();
             
             return redirect()
             ->back()
             ->withInput()
             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
         }
     }
 
     public function update(Request $req, $outComId)
     {
         // dd($req->all());
         Log::info('Request Inputs:', $req->all());
         
         try {
             $commodityIds = json_decode($req->input('commodity_id')[0], true);
             $quantities   = json_decode($req->input('quantity')[0], true);
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
                 'dateOut'        => 'nullable|date',
                 'type'           => 'nullable|integer|digits_between:0,3',
                 'outDetails'     => 'nullable|array',
                 'note'           => 'nullable|string|max:255',
             ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
             Log::error('Validation Error:', $e->errors());
             throw $e;
         }
     
         Log::info('Validated Inputs:', $validated);
     
         $outComm = OutCommodity::with(['commodities.stock'])->findOrFail($outComId);
         Log::info('Fetched OutCommodity Record:', $outComm->toArray());
     
         foreach ($commodityIds as $index => $commodityId) {
             $newQuantity = $quantities[$index] ?? null;
         
             if ($newQuantity === null) {
                 Log::error("Missing quantity for Commodity ID: $commodityId");
                 continue;
             }
         
             $oldCommodity = $outComm->commodities->firstWhere('commoditiesId', $commodityId);
         
             if ($oldCommodity) {
                 $oldQuantity = $oldCommodity->pivot->quantity;
         
                 if ($newQuantity != $oldQuantity) {
                     Log::info("Updating Commodity ID: $commodityId, Old Quantity: $oldQuantity, New Quantity: $newQuantity");
                     if ($newQuantity > $oldQuantity) {
                         $difference = $newQuantity - $oldQuantity;
                         $this->adjustStockForExistingCommodity($commodityId, -$difference, $outComm);
                     } elseif ($newQuantity < $oldQuantity) {
                         $difference = $oldQuantity - $newQuantity;
                         $this->adjustStockForExistingCommodity($commodityId, $difference, $outComm);
                     }
         
                     $outComm->commodities()->updateExistingPivot($commodityId, ['quantity' => $newQuantity]);
                 } else {
                     Log::info("No changes for Commodity ID: $commodityId");
                 }
             } else {
                 Log::info("Processing new Commodity ID: $commodityId, New Quantity: $newQuantity");
         
                 $this->adjustStockForNewCommodity($commodityId, $newQuantity);
         
                 $outComm->commodities()->attach($commodityId, ['quantity' => $newQuantity]);
             }
         }
     
         try {
            $type = $validated['type'];

            $outDetails = $validated['outDetails'] ?? [];

            if ($type === 'sales') {
                $outDetails = [
                    'customerName'   => $validated['outDetails']['customerName'] ?? null,
                    'customerPhone'  => $validated['outDetails']['customerPhone'] ?? null,
                    'paymentMethod'  => $validated['outDetails']['paymentMethod'] ?? null,
                ];
            } elseif ($type === 'service') {
                $outDetails = [
                    'serviceName'    => $validated['outDetails']['serviceName'] ?? null,
                    'servicePhone'   => $validated['outDetails']['servicePhone'] ?? null,
                    'serviceAddress' => $validated['outDetails']['serviceAddress'] ?? null,
                ];
            }

             $outComm->update([
                 'outDetails' => json_encode($outDetails),
                 'type'       => $validated['type'] ?? $outComm->type,
                 'note'       => $validated['note'] ?? $outComm->note,
             ]);
             Log::info('OutCommodity Updated Successfully:', $outComm->toArray());
         } catch (\Exception $e) {
             Log::error('Error updating outComm record:', ['error' => $e->getMessage()]);
             throw $e;
         }
     
         return redirect('transactions/outCommod')->with('success', 'Barang berhasil diperbarui.');
     }

 
     private function createStockTransaction($commodityId, $type, $quantity, $desc, $outCode = null)
     {
         try {
             $description = $outCode ? "$desc (OutCommodity Code: $outCode)" : $desc;
     
             StockTransac::create([
                 'user_id'      => Auth::user()->id, 
                 'commodity_id' => $commodityId,
                 'type'         => $type,
                 'quantity'     => $quantity,
                 'desc'         => $description,
             ]);
     
             Log::info("Stock transaction recorded. Commodity ID: $commodityId, Type: $type, Quantity: $quantity, Description: $description");
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
                 'quantity' => 'Stok barang tidak mencukupi untuk Barang Keluar.',
             ]);
         }
     
         $remainingQuantity = $newQuantity;
     
         foreach ($commodity->stock as $stock) {
             if ($remainingQuantity <= 0) break;
     
             if ($stock->quantity >= $remainingQuantity) {
                 $stock->update(['quantity' => $stock->quantity - $remainingQuantity]);
     
                 $this->createStockTransaction(
                     $commodityId,
                     StockTransac::TYPE_OUTUPDATE,
                     $remainingQuantity,
                     'Stok diperbarui untuk penyesuaian komoditas baru'
                 );
     
                 Log::info("Stock updated. Stock ID: {$stock->id}, Remaining Stock: {$stock->quantity}");
                 $remainingQuantity = 0;
             } else {
                 $this->createStockTransaction(
                     $commodityId,
                     StockTransac::TYPE_OUTUPDATE,
                     $stock->quantity,
                     'Stok habis untuk penyesuaian komoditas baru'
                 );
     
                 $remainingQuantity -= $stock->quantity;
                 $stock->update(['quantity' => 0]);
                 Log::info("Stock depleted. Stock ID: {$stock->id}, Remaining Quantity: $remainingQuantity");
             }
         }
     }
 
     private function adjustStockForExistingCommodity($commodityId, $quantityChange, $outComm)
     {
         $commodity = Commodity::with('stock')->findOrFail($commodityId);
     
         Log::info("Adjusting Stock for Existing Commodity ID: $commodityId, Quantity Change: $quantityChange");
     
         if ($quantityChange > 0) {
             foreach ($commodity->stock as $stock) {
                 if ($quantityChange <= 0) break;
     
                 $updatedQuantity = $stock->quantity + $quantityChange;
                 $stock->update(['quantity' => $updatedQuantity]);
     
                 $this->createStockTransaction($commodityId, StockTransac::TYPE_RETURNED, $quantityChange, 'Pengembalian Barang Dari Barang Keluar', $outComm->outCode);
 
     
                 Log::info("Stock returned. Stock ID: {$stock->id}, Updated Stock: {$stock->quantity}");
                 $quantityChange = 0;
             }
         } elseif ($quantityChange < 0) {
             $remainingQuantity = abs($quantityChange);
     
             foreach ($commodity->stock as $stock) {
                 if ($remainingQuantity <= 0) break;
     
                 if ($stock->quantity >= $remainingQuantity) {
                     $stock->update(['quantity' => $stock->quantity - $remainingQuantity]);
     
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
 
     public function delete($outComId)
     {
         DB::beginTransaction();
         try {
             $outCommod = OutCommodity::with('commodities')->findOrFail($outComId);
             $userId = Auth::user()->id; 
     
             foreach ($outCommod->commodities as $commodity) {
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
                     'desc' => 'Pengembalian stok dari dengan kode: ' . $outCommod->outCode,
                 ]);
             }
     
             $outCommod->commodities()->detach();
     
             $outCommod->update([
                 'outStatus' => OutCommodity::STATUS_DIHAPUS
             ]);
     
             DB::commit();
     
             return redirect('/transactions/outCommod/')
                 ->with('success', 'Barang berhasil dihapus, stok dikembalikan, dan transaksi tercatat.');
         } catch (\Exception $e) {
             DB::rollBack();
     
             return redirect()->back()
                 ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
         }
     }
}
