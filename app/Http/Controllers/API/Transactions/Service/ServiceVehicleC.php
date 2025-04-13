<?php

namespace App\Http\Controllers\API\Transactions\Service;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Resources\ManagementShift\EmployeeShift;
use App\Models\Resources\Merk\BrandMotor;
use App\Models\Resources\TypeVehicle\TypeVehicle;
use App\Models\Transactions\Commodity\Commodity;
use App\Models\Transactions\Service\ServicesV;
use App\Models\Transactions\Stock\StockTransac;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ServiceVehicleC extends Controller
{
    public function index(){
        $services = ServicesV::with(['brandMoto', 'commodities', 'empShift', 'typeVehicle'])
        ->get();
        return view('admin.transaction.servicev.index', compact('services'));
    }


    public function show($serVId){
       $services = ServicesV::with(['brandMoto', 'commodities', 'empShift', 'typeVehicle'])
                            ->findOrFail($serVId);
        return view('admin.transaction.servicev.details', compact('services'));
    }

    public function create()
    {
        $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
            ->where('type', Commodity::TYPE_SPAREPART)
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->with(['stock', 'category'])
            ->get();
    
        $lastService = ServicesV::where('servCode', 'LIKE', 'SRVM%')
            ->orderBy('servCode', 'desc')
            ->first();
    
        if ($lastService) {
            $lastNumber = (int)substr($lastService->servCode, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
    
        $servCode = 'SRVM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        $brand    = BrandMotor::where('status', BrandMotor::STATUS_ACTIVE)->get();
        $type     = TypeVehicle::where('status', TypeVehicle::STATUS_ACTIVE)->get();
        $today    = Carbon::today()->toDateString(); 
        $empShift = EmployeeShift::where('status', EmployeeShift::STATUS_ACTIVE)
            ->whereDate('date', $today) 
            ->whereHas('users', function ($query) {
                $query->role('teknisi'); 
            })
            ->get();
        return view('admin.transaction.servicev.create', compact('commodity', 'servCode', 'brand', 'type','empShift'));
    }

    public function invoice(){
        $services = ServicesV::with(['brandMoto', 'commodities', 'empShift'])
        ->get();

        $company = Company::first();
        return view('admin.transaction.servicev.invoice', compact('services', 'company'));
    }

    public function edit($serVId){
        $commodity = Commodity::where('status', Commodity::STATUS_ACTIVE)
            ->where('type', Commodity::TYPE_SPAREPART)
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->with(['stock'])
            ->get();
        $services = ServicesV::with(['brandMoto', 'commodities', 'empShift'])->findOrFail($serVId);
        $type     = TypeVehicle::where('status', TypeVehicle::STATUS_ACTIVE)->get();
        $brand    = BrandMotor::where('status', BrandMotor::STATUS_ACTIVE)->get();
        $today    = Carbon::today()->toDateString(); 

        $empShift = EmployeeShift::where('status', EmployeeShift::STATUS_ACTIVE)
        ->whereDate('date', $today)
        ->with('users') 
        ->get()
        ->filter(function ($shift) {
            return $shift->users && $shift->users->hasRole('teknisi'); 
        });
        return view('admin.transaction.servicev.update', compact('services', 'commodity', 'brand', 'type','empShift'));
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
                'dateService'    => 'required|date',
                'endService'     => 'nullable|date',
                'typeV_id'       => 'required|integer|exists:type_vehicles,TypeVId',
                'brandMoto_Id'   => 'required|integer|exists:brand_motors,brandMotorId',
                'Empshift_id'    => 'required|integer|exists:employee_shifts,empShiftId',
                'platNo'         => 'required|string|min:3|max:20',
                'customers'      => 'required|string|min:3|max:50',
                'phoneCustomers' => 'required|integer',
                'typeVehicle'    => 'required|integer',
                'typeServ'       => 'required|integer',
                'ServPrice'      => 'required|integer',
                'discount'       => 'required|numeric',
                'total'          => 'required',
                'desc'           => 'nullable|string|max:255',
            ]);
        
            $userId = Auth::user()->id;
            $totalQuantity = 0;
        
            $lastService = ServicesV::where('servCode', 'LIKE', 'SRVM%')
                ->orderBy('servCode', 'desc')
                ->first();
        
            if ($lastService) {
                $lastNumber = (int)substr($lastService->servCode, 5);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
        
            $servCode = 'SRVM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $newNumber++;  
        
            $service = ServicesV::create([
                'user_id'        => $userId,
                'servCode'       => $servCode,
                'dateService'    => $validated['dateService'],
                'endService'     => $validated['endService'],
                'brandMoto_Id'   => $validated['brandMoto_Id'],
                'typeV_id'       => $validated['typeV_id'],
                'Empshift_id'    => $validated['Empshift_id'],
                'platNo'         => $validated['platNo'],
                'customers'      => $validated['customers'],
                'phoneCustomers' => $validated['phoneCustomers'],
                'typeVehicle'    => $validated['typeVehicle'],
                'typeServ'       => $validated['typeServ'],
                'ServPrice'      => $validated['ServPrice'],
                'discount'       => $validated['discount'],
                'total'          => $validated['total'],
                'desc'           => $validated['desc'] ?? null,
                'statusServ'     => ServicesV::STATUS_PENDING,
            ]);
        
            foreach ($validated['commodity_id'] as $index => $commodityId) {
                $commodity = Commodity::findOrFail($commodityId);
                $quantity = $validated['quantity'][$index];
        
                $currentStock = $commodity->stock->sum('quantity');
                if ($quantity > $currentStock) {
                    return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Stok tidak mencukupi untuk servis kendaraan ' . $commodity->name . '.');
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
                    'user_id'      => $userId,
                    'commodity_id' => $commodity->commoditiesId,
                    'type'         => 2,
                    'quantity'     => $quantity,
                    'desc'         => 'Servis Kendaraan dengan kode: ' . $servCode,
                ]);
        
                $totalQuantity += $quantity;  
            }
    
            DB::commit();
        
            return redirect('/transactions/serviceV/')
                ->with('success', $totalQuantity . ' Data Servis kendaraan Berhasil ditambahkan.');
        
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $serVId)
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
                'dateService'    => 'nullable|date',
                'endService'     => 'nullable|date',
                'brandMoto_Id'   => 'nullable|integer|exists:brand_motors,brandMotorId',
                'typeV_id'       => 'nullable|integer|exists:type_vehicles,TypeVId',
                'Empshift_id'    => 'nullable|integer|exists:employee_shifts,empShiftId',
                'platNo'         => 'nullable|string|min:3|max:20',
                'customers'      => 'nullable|string|min:3|max:50',
                'phoneCustomers' => 'nullable|integer',
                'typeVehicle'    => 'nullable|integer',
                'typeServ'       => 'nullable|integer',
                'ServPrice'      => 'nullable|integer',
                'discount'       => 'nullable|numeric',
                'total'          => 'nullable',
                'desc'           => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            throw $e;
        }
    
        Log::info('Validated Inputs:', $validated);
    
        $service = ServicesV::with(['commodities.stock'])->findOrFail($serVId);
        Log::info('Fetched Service Record:', $service->toArray());
    
        if ($service->statusServ == ServicesV::STATUS_BATAL) {
            foreach ($service->commodities as $commodity) {
                $this->restoreStock($commodity->commoditiesId, $commodity->pivot->quantity);
            }
            Log::info('Stock restored for canceled service.', ['service_id' => $serVId]);
        }
        
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
                'dateService'    => $validated['dateService'] ?? $service->dateService,
                'endService'     => $validated['endService']?? $service->endService,
                'brandMoto_Id'   => $validated['brandMoto_Id']?? $service->brandMoto_Id,
                'typeV_id'       => $validated['typeV_id']?? $service->typeV_id,
                'Empshift_id'    => $validated['Empshift_id']?? $service->Empshift_id,
                'platNo'         => $validated['platNo']?? $service->platNo,
                'customers'      => $validated['customers']?? $service->customers,
                'phoneCustomers' => $validated['phoneCustomers']?? $service->phoneCustomers,
                'typeVehicle'    => $validated['typeVehicle']?? $service->typeVehicle,
                'typeServ'       => $validated['typeServ'] ?? $service->typeServ,
                'ServPrice'      => $validated['ServPrice']  ?? $service->ServPrice,
                'discount'       => $validated['discount']  ?? $service->discount,
                'total'          => $validated['total'] ?? $service->total,
                'desc'           => $validated['desc'] ?? $service->desc,
            ]);
            Log::info('Service Updated Successfully:', $service->toArray());
        } catch (\Exception $e) {
            Log::error('Error updating service record:', ['error' => $e->getMessage()]);
            throw $e;
        }
    
        return redirect('transactions/serviceV')->with('success', 'Servis Kendaraan berhasil diperbarui.');
    }

    private function restoreStock($commodityId, $quantity)  
    {
        $commodity = Commodity::find($commodityId);
        if ($commodity) {
            $commodity->stock += $quantity;
            $commodity->save();
            Log::info("Stock restored for Commodity ID: $commodityId, Quantity: $quantity");
        } else {
            Log::error("Commodity not found for ID: $commodityId");
        }
    }

    private function createStockTransaction($commodityId, $type, $quantity, $desc, $servCode = null)
    {
        try {
            $description = $servCode ? "$desc (Service Code: $servCode)" : $desc;
    
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
                'quantity' => 'Stok barang tidak mencukupi untuk Servis Kendaraan.',
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
    
                $this->createStockTransaction($commodityId, StockTransac::TYPE_RETURNED, $quantityChange, 'Pengembalian Barang Dari servis Kendaraan', $service->servCode);

    
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

    public function delete($serVId)
    {
        DB::beginTransaction();
        try {
            $services = ServicesV::with('commodities')->findOrFail($serVId);
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
                    'type'     => StockTransac::TYPE_RETURNED,
                    'quantity' => $quantity,
                    'desc'     => 'Pengembalian stok dari Servis Kendaraan dengan kode: ' . $services->servCode,
                ]);
            }
    
            $services->commodities()->detach();
    
            $services->update([
                'statusServ' => ServicesV::STATUS_DIHAPUS
            ]);
    
            DB::commit();
    
            return redirect('/transactions/serviceV/')
                ->with('success', 'Servis Barang berhasil dihapus, stok dikembalikan, dan transaksi tercatat.');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
