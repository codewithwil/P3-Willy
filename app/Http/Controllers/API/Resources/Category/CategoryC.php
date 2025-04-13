<?php

namespace App\Http\Controllers\API\Resources\Category;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Category\Category,
};
use App\Models\Resources\Company\Company;
use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator
};


class CategoryC extends Controller
{
    public function index(){
        $category = Category::where('status', Category::STATUS_ACTIVE)->get();
        return view('admin.resources.category.index', compact('category'));
    }

    public function invoice(){
        $category = Category::where('status', Category::STATUS_ACTIVE)->get();
        $company  = Company::first();
        return view('admin.resources.category.invoice', compact('category', 'company'));
    }

    public function create(){
        return view('admin.resources.category.create');
    }

    public function edit($categoryId){
        $category = Category::findOrFail($categoryId);
        return view('admin.resources.category.update', compact('category'));
    }

    public function store(Request $req){
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($req->all(), [
                'name'  => 'required|string|min:3|max:50',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            Category::create([
                'name'  => $req->input('name'),
            ]);
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Category berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $req, $categoryId)
    {
        $req->validate([
            'name'  => 'required|string|min:3|max:50',
        ]);
        DB::beginTransaction(); 
        try {
            $category       = Category::findOrFail($categoryId);
            $category->name = $req->name;
    
            $category->save();
    
            DB::commit(); 
    
            return redirect('/configuration/category/')->with('success', 'Data Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $category->status = Category::STATUS_INACTIVE;
            $category->save();
            $message = 'Data Kategori Berhasil Dihapus';
            return redirect('/configuration/category/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/configuration/category/')
                ->with('error', 'Kategori tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

