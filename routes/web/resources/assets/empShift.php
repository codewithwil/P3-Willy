<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'invoice'])->name("invoice");
Route::get("/edit/{empShiftId}", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'store'])->name("store");
Route::post("/update/{empShiftId}", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'update'])->name("update");
Route::post("/delete/{empShiftId}", [ctr\API\Resources\ManagementShift\EmployeeShift\EmployeeShiftC::class, 'delete'])->name("delete");