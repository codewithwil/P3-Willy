<?php

namespace App\Http\Controllers\API\Transactions\Commodity;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Building\Room,
    Models\Resources\Category\Category,
    Models\Resources\Unit\Unit,
    Models\Transactions\Commodity\Commodity,
    Models\Resources\Company\Company,
    Models\Transactions\Stock\Stock
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Storage,
};

class CommodityC extends Controller
{
    public function index(){
        $commodity  = Commodity::with(['room', 'category', 'unit', 'stock'])
                                ->where('status', Commodity::STATUS_ACTIVE)
                                ->get();
        return view('admin.transaction.commodity.index', compact('commodity'));
    }

    public function invoice(){
        $commodity  = Commodity::with(['room', 'category', 'unit', 'stock'])
        ->where('status', Commodity::STATUS_ACTIVE)
        ->get();

        $company = Company::first();
        return view('admin.transaction.commodity.invoice', compact('commodity', 'company'));
    }

    public function create(){
        $rooms     = Room::where('roomStatus', Room::STATUS_ACTIVE)->get();
        $category = Category::where('status', Category::STATUS_ACTIVE)->get();
        $unit     = Unit::where('status', Unit::STATUS_ACTIVE)->get();
        return view('admin.transaction.commodity.create', compact('rooms', 'category', 'unit'));
    }

    public function show($commoditiesId){
        $commodity = Commodity::with(['room', 'category', 'unit', 'stock'])->findOrFail($commoditiesId);

        return view('admin.transaction.commodity.details', compact('commodity'));
    }

    public function edit($commoditiesId){
        $commodity = Commodity::with(['room', 'category', 'unit', 'stock'])->findOrFail($commoditiesId);
        $rooms     = Room::where('roomStatus', Room::STATUS_ACTIVE)->get();
        $category  = Category::where('status', Category::STATUS_ACTIVE)->get();
        $unit      = Unit::where('status', Unit::STATUS_ACTIVE)->get();

        return view('admin.transaction.commodity.update', compact('commodity', 'rooms', 'category', 'unit'));
    }

    public function store(Request $req)
    {
        DB::beginTransaction(); 

        try {
            $validatedData = $req->validate([
                'name'          => 'required|string|max:255',
                'price'         => 'required|integer',
                'image'         => 'nullable|image|max:2048', 
                'room_Id'       => 'required|exists:rooms,roomId',
                'category_id'   => 'required|exists:categories,categoryId',
                'unit_id'       => 'required|exists:units,unitId',
                'merk'          => 'nullable|string|min:3|max:50',
                'type'          => 'required|integer|min:1|max:2',
                'desc'          => 'nullable|string|max:255',
                'initial_stock' => 'required|integer|min:0', 
            ]);

            // Proses unggah gambar jika ada
            $imagePath = null;
            if ($req->hasFile('image')) {
                $imagePath = $req->file('image')->store('commodities', 'public');
            }

            $commodity = Commodity::create([
                'name'        => $validatedData['name'],
                'image'       => $imagePath,
                'price'       => $validatedData['price'],
                'room_Id'     => $validatedData['room_Id'],
                'category_id' => $validatedData['category_id'],
                'unit_id'     => $validatedData['unit_id'],
                'merk'        => $validatedData['merk'],
                'type'        => $validatedData['type'],
                'desc'        => $validatedData['desc'] ?? null,
            ]);

            Stock::create([
                'commodity_id' => $commodity->commoditiesId,
                'quantity'     => $validatedData['initial_stock'],
            ]);

            DB::commit(); 

            return redirect('/transactions/commodities/')->with('success', 'Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $req, $commoditiesId)
    {
        DB::beginTransaction();
    
        try {
            $validatedData = $req->validate([
                'name'          => 'required|string|max:255',
                'price'         => 'required|integer',
                'image'         => 'nullable|image|max:2048',
                'room_Id'       => 'required|exists:rooms,roomId',
                'category_id'   => 'required|exists:categories,categoryId',
                'unit_id'       => 'required|exists:units,unitId',
                'merk'          => 'nullable|string|min:3|max:50',
                'type'          => 'nullable|integer|min:1|max:2',
                'desc'          => 'nullable|string',
                'initial_stock' => 'required|integer|min:0',
            ]);
    
            $commodity = Commodity::findOrFail($commoditiesId);
            if ($req->hasFile('image')) {
                if ($commodity->image) {
                    Storage::disk('public')->delete($commodity->image);
                }
                $imagePath = $req->file('image')->store('commodities', 'public');
            } else {
                $imagePath = $commodity->image;
            }
    
            $commodity->update([
                'name'        => $validatedData['name'],
                'price'       => $validatedData['price'],
                'image'       => $imagePath,
                'room_Id'     => $validatedData['room_Id'],
                'category_id' => $validatedData['category_id'],
                'unit_id'     => $validatedData['unit_id'],
                'merk'        => $validatedData['merk'],
                'type'        => $validatedData['type'],
                'desc'        => $validatedData['desc'] ?? null,
            ]);
    
            $stock = Stock::where('commodity_id', $commoditiesId)->first();
            if ($stock) {
                $stock->update([
                    'quantity' => $validatedData['initial_stock'],
                ]);
            }
    
            DB::commit();
    
            return redirect('/transactions/commodities/')->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($commoditiesId) {
        DB::beginTransaction();  
    
        try {
            $com         = Commodity::findOrFail($commoditiesId);
            $com->status = Commodity::STATUS_INACTIVE;
            $com->save();
            DB::commit();  
    
            $message = 'Data Barang Berhasil Dihapus';  
            return redirect('/transactions/commodities/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/transactions/commodities/')
                ->with('error', 'Barang tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
