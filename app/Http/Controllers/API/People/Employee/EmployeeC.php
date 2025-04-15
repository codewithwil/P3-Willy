<?php

namespace App\Http\Controllers\API\People\Employee;

use App\Http\Controllers\Controller;
use App\Models\People\Employee\Employee;
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

class EmployeeC extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin') && Auth::user()->branch_id === null) {
            $users = Employee::with('user.branch')->get();
        } else {
            $users = Employee::whereHas('user', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->with('user.branch')->get();
        }

        return view('admin.people.employee.index', compact('users'));
    }
    
    public function create(){
        $roles    = Role::all(); 
        $branch   = Branch::all(); 
        return view('admin.people.employee.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = Employee::all();
        $company = Company::first();
        return view('admin.people.employee.invoice', compact('users', 'company'));
    }

    public function edit($employeeId)
    {
        $users = Employee::with('user')->findOrFail($employeeId);
        $roles = Role::all();
        $branch   = Branch::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.employee.update', compact('users', 'roles', 'userRole', 'branch'));
    }
    

    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'name'     => 'required|string|max:255',
                'telepon'  => 'required|numeric',
                'role'     => 'required|in:employee,employee,petugas,owner,pengguna',
                'foto'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'address'  => 'required|string|max:255',
                'birthdate'  => 'required',
                'hire_date'  => 'required',
                'salary'  => 'required',
                'gender'  => 'required',
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
                'branch_id' => $request->input('branch_id'),
            ]);
    
            $user->assignRole($request->input('role'));
    
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('employee_foto', 'public');
            }
    
            Employee::create([
                'user_id' => $user->id,
                'name'    => $request->input('name'),
                'telepon' => $request->input('telepon'),
                'foto'    => $fotoPath,
                'address' => $request->input('address'),
                'birthdate' => $request->input('birthdate'),
                'hire_date' => $request->input('hire_date'),
                'salary' => $request->input('salary'),
                'gender' => $request->input('gender'),
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Data employee berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    


    public function update(Request $request, $employeeId)
    {
        $request->validate([
            'branch_id' => 'nullable',
            'email' => 'nullable|email',
            'password' => 'nullable|min:6',
            'name'     => 'nullable|string|max:255',
            'telepon'  => 'nullable|numeric',
            'role'     => 'nullable|in:employee,employee,petugas,owner,pengguna',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address'  => 'nullable|string|max:255',
            'birthdate'  => 'nullable',
            'hire_date'  => 'nullable',
            'salary'  => 'nullable',
            'gender'  => 'nullable',
        ]);
    
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($employeeId);
            $user  = $employee->user;
    
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
                if ($employee->foto && Storage::exists('public/' . $employee->foto)) {
                    Storage::delete('public/' . $employee->foto);
                }
    
                $file           = $request->file('foto');
                $fotoPath       = $file->store('employee_foto', 'public');  
                $employee->foto = $fotoPath;  
            }
    
            $employee->name      = $request->name;
            $employee->telepon   = $request->telepon;
            $employee->address   = $request->address;
            $employee->birthdate = $request->birthdate;
            $employee->hire_date = $request->hire_date;
            $employee->salary    = $request->salary;
            $employee->gender    = $request->gender;
            $employee->save();
    
            DB::commit();
            return redirect('/people/employee')->with('success', 'Data user berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $user = $employee->user;
            $user->roles()->detach();
            if ($employee->foto && Storage::exists('public/' . $employee->foto)) {
                Storage::delete('public/' . $employee->foto);
            }
    
            $employee->delete();
            $user->delete();
    
            DB::commit();
            
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/employee')->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect('/people/employee')->with('error', 'Employee tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
