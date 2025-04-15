<?php

namespace App\Http\Controllers\API\People\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\People\Supervisor\Supervisor;
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

class SupervisorC extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin') && Auth::user()->branch_id === null) {
            $users = Supervisor::with('user.branch')->get();
        } else {
            $users = Supervisor::whereHas('user', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->with('user.branch')->get();
        }

        return view('admin.people.supervisor.index', compact('users'));
    }
    
    public function create(){
        $roles    = Role::all(); 
        $branch    = Branch::all(); 
        return view('admin.people.supervisor.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = Supervisor::all();
        $company = Company::first();
        return view('admin.people.supervisor.invoice', compact('users', 'company'));
    }

    public function edit($supervisorId)
    {
        $users = Supervisor::with('user')->findOrFail($supervisorId);
        $roles = Role::all(); 
        $branch = Branch::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.supervisor.update', compact('users', 'roles', 'userRole', 'branch'));
    }
    

    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'branch_id' => 'required',
                'name'     => 'required|string|max:255',
                'telepon'  => 'required|numeric',
                'role'     => 'required|in:supervisor,supervisor,petugas,owner,pengguna',
                'foto'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
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
                'branch_id' =>  $request->input('branch_id'),
            ]);
    
            $user->assignRole($request->input('role'));
    
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('supervissor_foto', 'public');
            }
    
            Supervisor::create([
                'user_id' => $user->id,
                'name'    => $request->input('name'),
                'telepon' => $request->input('telepon'),
                'foto'    => $fotoPath,
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Data supervisor berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    


    public function update(Request $request, $supervisorId)
    {
        $request->validate([
            'email'     => 'nullable|email',
            'name'      => 'nullable|string|max:255',
            'telepon'   => 'nullable|digits_between:10,15',
            'password'  => 'nullable|min:8',
            'role'      => 'nullable|exists:roles,name',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_id' => 'nullable',
        ]);
    
        DB::beginTransaction();
        try {
            $supervisor = Supervisor::findOrFail($supervisorId);
            $user  = $supervisor->user;
    
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
                if ($supervisor->foto && Storage::exists('public/' . $supervisor->foto)) {
                    Storage::delete('public/' . $supervisor->foto);
                }
    
                $file     = $request->file('foto');
                $fotoPath = $file->store('supervissor_foto', 'public');  
                $supervisor->foto = $fotoPath;  
            }
    
            $supervisor->name    = $request->name;
            $supervisor->telepon = $request->telepon;
            $supervisor->save();
    
            DB::commit();
            return redirect('/people/supervisor')->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function delete($supervisorId)
    {
        DB::beginTransaction();
        try {
            $supervisor = Supervisor::findOrFail($supervisorId);
            $user = $supervisor->user;
            $user->roles()->detach();
            if ($supervisor->foto && Storage::exists('public/' . $supervisor->foto)) {
                Storage::delete('public/' . $supervisor->foto);
            }
    
            $supervisor->delete();
            $user->delete();
    
            DB::commit();
            
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/supervisor')->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect('/people/supervisor')->with('error', 'Supervisor tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
