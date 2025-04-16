<?php

namespace App\Http\Controllers\API\People\Owner;

use App\Http\Controllers\Controller;
use App\Models\People\Owner\Owner;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Company\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class OwnerC extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin') && Auth::user()->branch_id === null) {
            $users = Owner::with('user.branch')->get();
        } else {
            $users = Owner::whereHas('user', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->with('user.branch')->get();
        }

        return view('admin.people.owner.index', compact('users'));
    }
    
    public function create(){
        $roles    = Role::all(); 
        $branch    = Branch::all(); 
        return view('admin.people.owner.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = Owner::all();
        $company = Company::first();
        return view('admin.people.owner.invoice', compact('users', 'company'));
    }

    public function edit($ownerId)
    {
        $users = Owner::with('user')->findOrFail($ownerId);
        $roles = Role::all(); 
        $branch = Branch::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.owner.update', compact('users', 'roles', 'userRole', 'branch'));
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
                'role'     => 'required|in:owner,owner,petugas,owner,pengguna',
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
                $fotoPath = $request->file('foto')->store('owner_foto', 'public');
            }
    
            Owner::create([
                'user_id' => $user->id,
                'name'    => $request->input('name'),
                'telepon' => $request->input('telepon'),
                'foto'    => $fotoPath,
                'address' => $request->input('address'),
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Data owner berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    


    public function update(Request $request, $ownerId)
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
            $owner = Owner::findOrFail($ownerId);
            $user  = $owner->user;
    
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
    
            if ($request->filled('role')) {
                $user->syncRoles($request->role);
            }
    
            if ($request->hasFile('foto')) {
                if ($owner->foto && Storage::exists('public/' . $owner->foto)) {
                    Storage::delete('public/' . $owner->foto);
                }
    
                $file     = $request->file('foto');
                $fotoPath = $file->store('owner_foto', 'public');  
                $owner->foto = $fotoPath;  
            }
    
            $owner->name    = $request->name;
            $owner->telepon = $request->telepon;
            $owner->address    = $request->address;
            $owner->save();
    
            DB::commit();
            return redirect('/people/owner')->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function delete($ownerId)
    {
        DB::beginTransaction();
        try {
            $owner = Owner::findOrFail($ownerId);
            $user = $owner->user;
            $user->roles()->detach();
            if ($owner->foto && Storage::exists('public/' . $owner->foto)) {
                Storage::delete('public/' . $owner->foto);
            }
    
            $owner->delete();
            $user->delete();
    
            DB::commit();
            
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/owner')->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect('/people/owner')->with('error', 'Owner tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
