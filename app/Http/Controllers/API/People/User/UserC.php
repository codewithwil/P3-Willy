<?php

namespace App\Http\Controllers\API\People\User;

use App\{
    Http\Controllers\Controller,
    Models\User,
};
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Company\Company;
use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Hash,
    Support\Facades\Validator
};
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserC extends Controller
{
  
    public function index()
    {
        $branch = Branch::all();
        if (Auth::user()->branch && Auth::user()->branch->branchName === 'Administrator') {
            $users = User::with('branch')->get();
        } else {
            $users = User::where('branch_id', Auth::user()->branch_id)->get();
        }
        return view('admin.users.index', compact('users', 'branch'));
    }

    public function create(){
        $branch = Branch::all();
        $roles    = Role::all(); 
        return view('admin.users.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = User::all();
        $company = Company::first();
        return view('admin.users.invoice', compact('users', 'company'));
    }

    public function edit($id)
    {
        $users    = User::findOrFail($id);
        $roles    = Role::all(); 
        $userRole = $users->getRoleNames()->first();
    
        return view('admin.users.update', compact('users', 'roles', 'userRole'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|exists:branches,branchName',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|min:6',
                'name'      => 'required|string|max:255',
                'phone'     => 'required|numeric',
                'address'   => 'required|string|max:255',
                'role'      => 'required|in:admin,supervisor,petugas,owner,pengguna', 
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            $hashedPassword = Hash::make($request->input('password'));
            $user = User::create([
                'branch_id' => $request->input('branch_id'),
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => $hashedPassword,
                'phone'     => $request->input('phone'),
                'address'   => $request->input('address'),
            ]);

            $role = $request->input('role');
            $user->assignRole($role);
    
            DB::commit();
    
            return redirect('/people/users')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,branchName',
            'email'    => 'nullable|email|unique:users,email,' . $id,
            'name'     => 'nullable|string|max:255',
            'phone'    => 'nullable|digits_between:10,15',
            'address'  => 'nullable|string',
            'role'     => 'nullable|exists:roles,name', 
            'password' => 'nullable|min:8', 
        ]);
        DB::beginTransaction(); 
        try {
            $user = User::findOrFail($id);
            $user->branch_id   = $request->branch_id;
            $user->email   = $request->email;
            $user->name    = $request->name;
            $user->phone   = $request->phone;
            $user->address = $request->address;
    
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
    
            $user->save();
            $user->syncRoles($request->role);
    
            DB::commit(); 
    
            return redirect('/people/users')->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
    
            $user->roles()->detach(); 
    
            // Delete the user
            $user->delete();
    
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/users')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/people/users')
                ->with('error', 'User tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
}
