<?php

namespace App\Http\Controllers\API\Resources\Promo;

use App\Http\Controllers\Controller;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Company\Company;
use App\Models\Resources\Promo\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromoC extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin') && Auth::user()->branch_id === null) {
            $promo = Promo::where('status', Promo::STATUS_ACTIVE)->with('branch')->get();
        } else {
            $promo = Promo::where('branch_id', Auth::user()->branch_id)->where('status', Promo::STATUS_ACTIVE)->get();
        }
        return view('admin.resources.promo.index', compact('promo'));
    }

    public function invoice(){
        $promo    = Promo::where('status', Promo::STATUS_ACTIVE)->get();
        $company = Company::first();
        return view('admin.resources.promo.invoice', compact('promo', 'company'));
    }

    public function create()
    {
        $company = Company::first();
        $branch = Branch::all();

        $lastPromo = Promo::where('promoCode', 'like', 'PRMO%')
                        ->orderBy('promoCode', 'desc')
                        ->first();

        if ($lastPromo) {
            $lastNumber = (int) substr($lastPromo->promoCode, 4); 
        } else {
            $lastNumber = 0;
        }

        $promoCode = 'PRMO' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return view('admin.resources.promo.create', compact('company', 'promoCode', 'lastNumber', 'branch'));
    }


    public function edit($promoId)
    {
        $company = Company::first();
        $branch = Branch::all();
        $promo = Promo::findOrFail($promoId);
        return view('admin.resources.promo.update', compact('promo', 'company', 'branch'));
    }


    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req->all(), [
                'promoCode'         => 'required|string|max:20',
                'target_audience'   => 'required',
                'branch_id'         => 'nullable',
                'promoName'         => 'required|string|min:3|max:255',
                'description'       => 'nullable|string|min:3|max:255',
                'startDate'         => 'required',
                'endDate'           => 'required',
                'typePromo'         => 'required|digits_between:1,2',
                'amountPromo'       => 'required',  
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return redirect()->back()->with('error', $errors)->withInput();
            }
    
            $promo = Promo::create([
                'promoCode'    => $req->input('promoCode'),
                'target_audience'    => $req->input('target_audience'),
                'branch_id'    => $req->input('branch_id'),
                'promoName'    => $req->input('promoName'),
                'description'  => $req->input('description'),
                'startDate'    => $req->input('startDate'),
                'endDate'      => $req->input('endDate'),
                'typePromo'    => $req->input('typePromo'),
                'amountPromo'  => $req->input('amountPromo'),
            ]);
    
            DB::commit();
    
            return redirect('/setting/promo/')->with('success', 'Promo berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function update(Request $req, $promoId)
    {
        $req->validate([
            'promoCode'         => 'nullable|string|max:20',
            'target_audience'   => 'nullable',
            'branch_id'         => 'nullable',
            'promoName'         => 'nullable|string|min:3|max:255',
            'description'       => 'nullable|string|min:3|max:255',
            'startDate'         => 'nullable',
            'endDate'           => 'nullable',
            'typePromo'         => 'nullable|digits_between:1,2',
            'amountPromo'       => 'nullable',  
        ]);
        
        DB::beginTransaction();
        try {
            $promo              = Promo::findOrFail($promoId);
            $promo->promoCode   = $req->promoCode;
            $promo->target_audience   = $req->target_audience;
            $promo->branch_id   = $req->branch_id;
            $promo->promoName   = $req->promoName;
            $promo->description = $req->description;
            $promo->startDate   = $req->startDate;
            $promo->endDate     = $req->endDate;
            $promo->typePromo   = $req->typePromo;
            $promo->amountPromo = $req->amountPromo;

            $promo->save();

            DB::commit();

            return redirect('/setting/promo/')->with('success', 'Data promo berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($promoId) {
        DB::beginTransaction();  
    
        try {
            $promo         = Promo::findOrFail($promoId);
            $promo->status = Promo::STATUS_NOTACTIVE;
            $promo->save();
            DB::commit();  
    
            $message = 'Data Promo Berhasil Dihapus';  
            return redirect('/setting/promo/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/setting/promo/')
                ->with('error', 'Promo tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
