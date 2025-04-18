<?php

namespace App\Http\Controllers\API\People\Customer;

use App\Http\Controllers\Controller;
use App\Models\People\Customers\Customers;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Company\Company;
use App\Models\Transactions\Saldo\SaldoHistories;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Spatie\Permission\Models\Role;

class CustomerC extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin') && Auth::user()->branch_id === null) {
            $users = Customers::with('user.branch')->get();
        } else {
            $users = Customers::whereHas('user', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->with('user.branch')->get();
        }

        return view('admin.people.customer.index', compact('users'));
    }
    
    public function create(){
        $roles    = Role::all(); 
        $branch    = Branch::all(); 
        return view('admin.people.customer.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = Customers::all();
        $company = Company::first();
        return view('admin.people.customer.invoice', compact('users', 'company'));
    }

    public function profile($customerId){
        $users = Customers::with('user')->findOrFail($customerId);
        return view('front.profile.index', compact('users'));
    }

    public function edit($customerId)
    {
        $users = Customers::with('user')->findOrFail($customerId);
        $roles = Role::all(); 
        $branch = Branch::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.customer.update', compact('users', 'roles', 'userRole', 'branch'));
    }
    

    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'name'     => 'required|string|max:255',
                'telepon'  => 'required|numeric',
                'role'     => 'required|in:customer,customer,petugas,customer,pengguna',
                'foto'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'address'     => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
    
            $user = User::create([
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'branch_id' =>  null,
            ]);
    
            $user->assignRole($request->input('role'));
    
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('customer_foto', 'public');
            }
    
            Customers::create([
                'user_id' => $user->id,
                'name'    => $request->input('name'),
                'telepon' => $request->input('telepon'),
                'foto'    => $fotoPath,
                'address' => $request->input('address'),
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Data customer berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    


    public function update(Request $request, $customerId)
    {
        $request->validate([
            'email'     => 'nullable|email',
            'name'      => 'nullable|string|max:255',
            'telepon'   => 'nullable|digits_between:10,15',
            'password'  => 'nullable|min:8',
            'role'      => 'nullable|exists:roles,name',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address'      => 'nullable|string|max:255',
        ]);
    
        DB::beginTransaction();
        try {
            $customer = Customers::findOrFail($customerId);
            $user  = $customer->user;
    
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->branch_id = $request->branch_id;
            $user->save();
    
            if ($request->filled('role')) {
                $user->syncRoles($request->role);
            }
    
            if ($request->hasFile('foto')) {
                if ($customer->foto && Storage::exists('public/' . $customer->foto)) {
                    Storage::delete('public/' . $customer->foto);
                }
    
                $file     = $request->file('foto');
                $fotoPath = $file->store('customer_foto', 'public');  
                $customer->foto = $fotoPath;  
            }
    
            $customer->name    = $request->name;
            $customer->telepon = $request->telepon;
            $customer->address    = $request->address;
            $customer->save();
    
            DB::commit();
            return redirect('/people/customer')->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function profileUpdate(Request $request, $customerId)
    {
        $request->validate([
            'email'     => 'nullable|email',
            'name'      => 'nullable|string|max:255',
            'telepon'   => 'nullable|digits_between:10,15',
            'password'  => 'nullable|min:8',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address'      => 'nullable|string|max:255',
        ]);
    
        DB::beginTransaction();
        try {
            $customer = Customers::findOrFail($customerId);
            $user  = $customer->user;
    
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->branch_id = $request->branch_id;
            $user->save();
    
            if ($request->filled('role')) {
                $user->syncRoles($request->role);
            }
    
            if ($request->hasFile('foto')) {
                if ($customer->foto && Storage::exists('public/' . $customer->foto)) {
                    Storage::delete('public/' . $customer->foto);
                }
    
                $file     = $request->file('foto');
                $fotoPath = $file->store('customer_foto', 'public');  
                $customer->foto = $fotoPath;  
            }
    
            $customer->name    = $request->name;
            $customer->telepon = $request->telepon;
            $customer->address    = $request->address;
            $customer->save();
    
            DB::commit();
            return redirect()->back()->with('success', 'Data profile berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function delete($customerId)
    {
        DB::beginTransaction();
        try {
            $customer = Customers::findOrFail($customerId);
            $user = $customer->user;
            $user->roles()->detach();
            if ($customer->foto && Storage::exists('public/' . $customer->foto)) {
                Storage::delete('public/' . $customer->foto);
            }
    
            $customer->delete();
            $user->delete();
    
            DB::commit();
            
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/customer')->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect('/people/customer')->with('error', 'Customers tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function topup($customerId){
        $users = Customers::with('user')->findOrFail($customerId);
        return view('front.topup.index', compact('users'));
    }

    public function topupStore(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    
        $order_id = 'invoice-' . time();
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => 50000,
        ];
    
        $customer_details = [
            'first_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->telepon,
        ];
    
        $item_details = [[
            'id' => 'item-1',
            'price' => $request->amount,
            'quantity' => 1,
            'name' => 'Top Up saldo',
        ]];
    
        $userId = $request->Id; 

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'custom_fields' => [
                'custom_field1' => $userId,
            ],
        ];
    
        try {
            session([
                'user_id_to_register' => $userId,
                'topup_amount' => $request->amount, 
            ]); 
            $snapToken = Snap::getSnapToken($transaction_data);
            return view('front.topup.payment', compact('snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function success()
    {
        $userId = session('user_id_to_register');
        $amount = session('topup_amount');
    
        $customer = Customers::where('user_id', $userId)->first();
    
        DB::beginTransaction();
        try {
            if ($customer) {
                SaldoHistories::create([
                    'customer_id' => $customer->customerId,
                    'amount'      => $amount,
                    'type'        => SaldoHistories::TYPE_DEPOSIT,
                    'description' => 'Top up saldo via Midtrans',
                ]);
    
                $customer->saldo += $amount;
                $customer->save();
            }
    
            DB::commit();
    
            session()->forget(['user_id_to_register', 'topup_amount']);
    
            return view('front.topup.success');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan top up: ' . $e->getMessage());
        }
    }

    public function historySaldo($customerId)
    {
        $users = Customers::with('saldoHistories')->findOrFail($customerId);
        return view('front.topup.history', compact('users'));
    }
    
    
}
