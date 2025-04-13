<?php

namespace App\Http\Controllers\API\Transactions\ComeCommodity;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Resources\Supplier\Supplier;
use App\Models\Transactions\ComeCommodity\ComeCommodity;
use App\Models\Transactions\ComeCommodity\ComeCommodityPivot;
use App\Models\Transactions\Commodity\Commodity;
use App\Models\Transactions\Stock\StockTransac;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComeCommodityC extends Controller
{
    public function index(){
        $comeCommod = ComeCommodity::with(['users','commodities' , 'supplier'])
                                    ->where('statusComeCom', '!=', ComeCommodity::STATUS_DIHAPUS)
                                    ->get();
        return view('admin.transaction.comeCommod.index', compact('comeCommod'));
    }

    public function show($comeComdId){
        $comeCommod = ComeCommodity::with(['users', 'commodities', 'supplier'])->findOrFail($comeComdId);

        return view('admin.transaction.comeCommod.details', compact('comeCommod')); 
     }
 
     public function create()
     {
         $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
             ->whereHas('stock', function ($query) {
                 $query->where('quantity', '>', 0);
             })
             ->with(['stock', 'category'])
             ->get();
     
             $lastCode = ComeCommodity::where('comComeCode', 'LIKE', 'PMBG%')
             ->orderByRaw("CAST(SUBSTRING(comComeCode, 5, LENGTH(comComeCode)) AS UNSIGNED) DESC")
             ->first();
         
     
         if ($lastCode) {
             $lastNumber = (int)substr($lastCode->comComeCode, 5);
             $newNumber = $lastNumber + 1;
         } else {
             $newNumber = 1;
         }
     
         $comComeCode = 'PMBG' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
         $supplier    = Supplier::where('status', Supplier::STATUS_ACTIVE)->get();
         return view('admin.transaction.comeCommod.create', compact('commodity', 'comComeCode', 'supplier'));
     }
 
     public function invoice(){
         $comeCommod = ComeCommodity::with(['users', 'commodities'])
         ->where('statusComeCom', '!=', ComeCommodity::STATUS_DIHAPUS)
         ->get();
 
         $company = Company::first();
         return view('admin.transaction.comeCommod.invoice', compact('comeCommod', 'company'));
     }
 

     public function edit($comeComdId){
         $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
             ->whereHas('stock', function ($query) {
                 $query->where('quantity', '>', 0);
             })
             ->with(['stock'])
             ->get();
         $comeCommod = ComeCommodity::with(['users', 'commodities'])->findOrFail($comeComdId);
         $supplier = Supplier::where('status', Supplier::STATUS_ACTIVE)->get();
         return view('admin.transaction.comeCommod.update', compact('comeCommod', 'commodity', 'supplier'));
     }

     public function store(Request $req)
     {
         DB::beginTransaction();
     
         try {
             // Menggabungkan commodity_id dan quantity dari form
             $req->merge([
                 'commodity_id'  => json_decode($req->input('commodity_id')[0], true),
                 'quantity'      => json_decode($req->input('quantity')[0], true),
             ]);
     
             // Validasi data
             $validated = $req->validate([
                 'commodity_id'   => 'required|array',
                 'commodity_id.*' => 'exists:commodities,commoditiesId', 
                 'quantity'       => 'required|array',
                 'quantity.*'     => 'required|integer|min:1',
                 'supplier_id'    => 'required|exists:suppliers,supplierId', 
                 'date'           => 'required|date',
                 'payment'        => 'required|integer',
                 'total'          => 'required',
                 'note'           => 'nullable|string|max:255',
             ]);
     
             $userId = Auth::user()->id;
             $totalQuantity = 0;
     
             $lastComeCommod = ComeCommodity::where('comComeCode', 'LIKE', 'PMBG%')
                 ->orderBy('comComeCode', 'desc')
                 ->first();
     
             if ($lastComeCommod) {
                 $lastNumber = (int)substr($lastComeCommod->comComeCode, 5);
                 $newNumber = $lastNumber + 1;
             } else {
                 $newNumber = 1;
             }
     
             $comComeCode = 'PMBG' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
             $newNumber++;
     
             // Insert ke come_commodities tanpa commodity_id
             $comeCommod = ComeCommodity::create([
                 'comComeCode'   => $comComeCode,
                 'supplier_id'   => $validated['supplier_id'],
                 'user_id'       => $userId,
                 'date'          => $validated['date'],
                 'note'          => $validated['note'] ?? null,
                 'payment'       => $validated['payment'] ?? null,
                 'total'         => $validated['total'] ?? null,
                 'statusComeCom' => ComeCommodity::STATUS_DIAJUKAN,
             ]);
     
             $commodityIds = $validated['commodity_id'];
             $quantities = $validated['quantity'];
     
             // Insert ke pivot table untuk menghubungkan come_commodities dan commodities
             foreach ($commodityIds as $index => $commodityId) {
                 $quantity = $quantities[$index];
                 ComeCommodityPivot::create([
                     'comeCom_id'    => $comeCommod->comeComdId,  
                     'commodity_id'  => $commodityId,              
                     'quantity'      => $quantity,
                 ]);
     
                 $totalQuantity += $quantity; 
             }
     
             DB::commit();
     
             return redirect('/transactions/comeCommod/')
                 ->with('success', $totalQuantity . ' barang berhasil ditambahkan.');
     
         } catch (Exception $e) {
             DB::rollBack();
             
             return redirect()
                 ->back()
                 ->withInput()
                 ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
         }
     }
     
     
 
     public function update(Request $req, $comeComdId)
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
                 'supplier_id'    => 'nullable|exists:suppliers,supplierId', 
                 'date'           => 'nullable|date',
                 'payment'        => 'nullable|integer',
                 'total'          => 'nullable',
                 'note'           => 'nullable|string|max:255',
             ]);
         } catch (\Illuminate\Validation\ValidationException $e) {
             Log::error('Validation Error:', $e->errors());
             throw $e;
         }
     
         Log::info('Validated Inputs:', $validated);
     
         $comeCommod = ComeCommodity::with(['commodities.stock'])->findOrFail($comeComdId);
         Log::info('Fetched ComeCommodity Record:', $comeCommod->toArray());
     
         foreach ($commodityIds as $index => $commodityId) {
             $newQuantity = $quantities[$index] ?? null;
         
             if ($newQuantity === null) {
                 Log::error("Missing quantity for Commodity ID: $commodityId");
                 continue;
             }
         
             $oldCommodity = $comeCommod->commodities->firstWhere('commoditiesId', $commodityId);
         
             if ($oldCommodity) {
                 $oldQuantity = $oldCommodity->pivot->quantity;
         
                 if ($newQuantity != $oldQuantity) {
                     $comeCommod->commodities()->updateExistingPivot($commodityId, ['quantity' => $newQuantity]);
                 } else {
                     Log::info("No changes for Commodity ID: $commodityId");
                 }
             } else {
                 Log::info("Processing new Commodity ID: $commodityId, New Quantity: $newQuantity");
                 $comeCommod->commodities()->attach($commodityId, ['quantity' => $newQuantity]);
             }
         }
     
         try {
             $comeCommod->update([
                 'payment' => $validated['payment'] ?? $comeCommod->payment,
                 'date' => $validated['date'] ?? $comeCommod->date,
                 'supplier_id' => $validated['supplier_id'] ?? $comeCommod->supplier_id,
                 'total' => $validated['total'] ?? $comeCommod->total,
                 'note' => $validated['note'] ?? $comeCommod->note,
             ]);
             Log::info('ComeCommodity Updated Successfully:', $comeCommod->toArray());
         } catch (\Exception $e) {
             Log::error('Error updating comeCommod record:', ['error' => $e->getMessage()]);
             throw $e;
         }
     
         return redirect('transactions/comeCommod')->with('success', 'Peminjaman berhasil diperbarui.');
     }

     public function delete($comeComdId)
     {
         DB::beginTransaction();
         try {
             $outCommod = ComeCommodity::with('commodities')->findOrFail($comeComdId);
             $userId = Auth::user()->id; 

             $outCommod->update([
                 'statusComeCom' => ComeCommodity::STATUS_DIHAPUS
             ]);
     
             DB::commit();
     
             return redirect('/transactions/comeCommod/')
                 ->with('success', 'Barang berhasil dihapus, stok dikembalikan, dan transaksi tercatat.');
         } catch (\Exception $e) {
             DB::rollBack();
     
             return redirect()->back()
                 ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
         }
     }
}
