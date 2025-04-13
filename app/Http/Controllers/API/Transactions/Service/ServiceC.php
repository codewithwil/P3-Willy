<?php

namespace App\Http\Controllers\API\Transactions\Service;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Transactions\Commodity\Commodity;
use App\Models\Transactions\Service\Service;
use App\Models\Transactions\Stock\StockTransac;
use Exception;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ServiceC extends Controller
{
    public function index(){
        $services = Service::with(['users', 'commodities'])
        ->where('serviceStatus', '!=', Service::STATUS_DIHAPUS)
        ->get();
        return view('admin.transaction.services.index', compact('services'));
    }

    public function show($serviceId){
       $services = Service::with(['users', 'commodities'])
                            ->findOrFail($serviceId);
        return view('admin.transaction.services.details', compact('services'));
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
    
        $lastService = Service::where('serviceCode', 'LIKE', 'SRV%')
            ->orderBy('serviceCode', 'desc')
            ->first();
    
        if ($lastService) {
            $lastNumber = (int)substr($lastService->serviceCode, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
    
        $serviceCode = 'SRV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
        return view('admin.transaction.services.create', compact('commodity', 'serviceCode'));
    }

    public function invoice(){
        $services = Service::with(['users', 'commodities'])
        ->where('serviceStatus', '!=', Service::STATUS_DIHAPUS)
        ->get();

        $company = Company::first();
        return view('admin.transaction.services.invoice', compact('services', 'company'));
    }

    public function edit($serviceId){
        $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
        ->where('type', Commodity::TYPE_ALAT)
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->with(['stock'])
            ->get();
        $services = Service::with(['users', 'commodities'])->findOrFail($serviceId);
        return view('admin.transaction.services.update', compact('services', 'commodity'));
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
                'serviceDate'    => 'required|date',
                'serviceName'    => 'required|string|min:3|max:50',
                'servicePhone' => 'required|integer|digits_between:10,16',
                'serviceAddress' => 'required|string|min:3|max:255',
                'note'           => 'nullable|string|max:255',
            ]);
        
            $userId = Auth::user()->id;
            $totalQuantity = 0;
        
            $lastService = Service::where('serviceCode', 'LIKE', 'SRV%')
                ->orderBy('serviceCode', 'desc')
                ->first();
        
            if ($lastService) {
                $lastNumber = (int)substr($lastService->serviceCode, 3);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
        
            $serviceCode = 'SRV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $newNumber++;  
        
            $service = Service::create([
                'user_id'        => $userId,
                'serviceCode'    => $serviceCode,
                'serviceDate'    => $validated['serviceDate'],
                'serviceName'    => $validated['serviceName'],
                'serviceAddress' => $validated['serviceAddress'],
                'servicePhone'   => $validated['servicePhone'],
                'note'           => $validated['note'] ?? null,
                'serviceStatus'  => Service::STATUS_DIAJUKAN,
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
        
                $service->commodities()->attach($commodity->commoditiesId, ['quantity' => $quantity]);
                
                StockTransac::create([
                    'user_id' => $userId,
                    'commodity_id' => $commodity->commoditiesId,
                    'type' => 2,
                    'quantity' => $quantity,
                    'desc' => 'Servis barang dengan kode: ' . $serviceCode,
                ]);
        
                $totalQuantity += $quantity;  
            }
    
            DB::commit();
        
            return redirect('/transactions/services/')
                ->with('success', $totalQuantity . ' barang berhasil ditambahkan untuk diservis.');
        
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $serviceId)
    {
        // dd($req->all());
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
                'serviceDate'    => 'nullable|date',
                'serviceName'    => 'nullable|string|min:3|max:50',
                'servicePhone'   => 'nullable|integer|digits_between:10,16',
                'serviceAddress' => 'nullable|string|min:3|max:255',
                'note'           => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            throw $e;
        }
    
        Log::info('Validated Inputs:', $validated);
    
        $service = Service::with(['commodities.stock'])->findOrFail($serviceId);
        Log::info('Fetched Service Record:', $service->toArray());
    
        foreach ($commodityIds as $index => $commodityId) {
            $newQuantity = $quantities[$index] ?? null;
        
            if ($newQuantity === null) {
                Log::error("Missing quantity for Commodity ID: $commodityId");
                continue;
            }
        
            $oldCommodity = $service->commodities->firstWhere('commoditiesId', $commodityId);
        
            if ($oldCommodity) {
                $oldQuantity = $oldCommodity->pivot->quantity;
        
                if ($newQuantity != $oldQuantity) {
                    Log::info("Updating Commodity ID: $commodityId, Old Quantity: $oldQuantity, New Quantity: $newQuantity");
                    if ($newQuantity > $oldQuantity) {
                        $difference = $newQuantity - $oldQuantity;
                        $this->adjustStockForExistingCommodity($commodityId, -$difference, $service);
                    } elseif ($newQuantity < $oldQuantity) {
                        $difference = $oldQuantity - $newQuantity;
                        $this->adjustStockForExistingCommodity($commodityId, $difference, $service);
                    }
        
                    $service->commodities()->updateExistingPivot($commodityId, ['quantity' => $newQuantity]);
                } else {
                    Log::info("No changes for Commodity ID: $commodityId");
                }
            } else {
                Log::info("Processing new Commodity ID: $commodityId, New Quantity: $newQuantity");
        
                $this->adjustStockForNewCommodity($commodityId, $newQuantity);
        
                $service->commodities()->attach($commodityId, ['quantity' => $newQuantity]);
            }
        }
    
        try {
            $service->update([
                'note' => $validated['note'] ?? $service->note,
            ]);
            Log::info('Service Updated Successfully:', $service->toArray());
        } catch (\Exception $e) {
            Log::error('Error updating service record:', ['error' => $e->getMessage()]);
            throw $e;
        }
    
        return redirect('transactions/services')->with('success', 'Servis Barang berhasil diperbarui.');
    }

    public function return(Request $req, $serviceId)
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
    
            DB::transaction(function () use ($serviceId, $commodityIds, $quantities) {
                $service = Service::with('commodities')->findOrFail($serviceId);
    
                if ($service->serviceStatus !== Service::STATUS_DISERVIS) {
                    throw new \Exception('Servis Barang tidak valid untuk dikembalikan.');
                }
    
                $service->update([
                    'returnServiceDate' => now(),
                    'serviceStatus' => Service::STATUS_SELESAI,
                ]);
    
                foreach ($commodityIds as $index => $commodityId) {
                    $newQuantity = $quantities[$index];
                    $existingCommodity = $service->commodities->firstWhere('commoditiesId', $commodityId);
    
                    if (!$existingCommodity) {
                        throw new \Exception("Commodity ID: $commodityId not found in service $service.");
                    }
    
                    $oldQuantity = $existingCommodity->pivot->quantity;
    
                  if ($newQuantity != $oldQuantity) {
                    $difference = $newQuantity - $oldQuantity;
                    Log::info("Adjusting stock for Commodity ID: $commodityId, Difference: $difference");
                    
                    $this->adjustStockForExistingCommodity($commodityId, $difference, $service);
                } else {
                    Log::info("No quantity change for Commodity ID: $commodityId, confirming return.");
                    $this->adjustStockForExistingCommodity($commodityId, $oldQuantity, $service); 
                }
                
                }
            });
    
            return redirect()->back()->with('success', 'Barang Selesai Di servis dan dikembalikan ke stok.');
        } catch (\Exception $e) {
            Log::error('Error returning service$service: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengembalikan pinjaman.');
        }
    }

    private function createStockTransaction($commodityId, $type, $quantity, $desc, $serviceCode = null)
    {
        try {
            $description = $serviceCode ? "$desc (Service Code: $serviceCode)" : $desc;
    
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
                'quantity' => 'Stok barang tidak mencukupi untuk peminjaman.',
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

    private function adjustStockForExistingCommodity($commodityId, $quantityChange, $service)
    {
        $commodity = Commodity::with('stock')->findOrFail($commodityId);
    
        Log::info("Adjusting Stock for Existing Commodity ID: $commodityId, Quantity Change: $quantityChange");
    
        if ($quantityChange > 0) {
            foreach ($commodity->stock as $stock) {
                if ($quantityChange <= 0) break;
    
                $updatedQuantity = $stock->quantity + $quantityChange;
                $stock->update(['quantity' => $updatedQuantity]);
    
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

    public function delete($serviceId)
    {
        DB::beginTransaction();
        try {
            $services = Service::with('commodities')->findOrFail($serviceId);
            $userId = Auth::user()->id; 
    
            foreach ($services->commodities as $commodity) {
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
                    'desc' => 'Pengembalian stok dari Servis dengan kode: ' . $services->serviceCode,
                ]);
            }
    
            $services->commodities()->detach();
    
            $services->update([
                'serviceStatus' => Service::STATUS_DIHAPUS
            ]);
    
            DB::commit();
    
            return redirect('/transactions/services/')
                ->with('success', 'Servis Barang berhasil dihapus, stok dikembalikan, dan transaksi tercatat.');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
