<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\Employee\EmployeeC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\People\Employee\EmployeeC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\People\Employee\EmployeeC::class, 'invoice'])->name("invoice");
Route::get("/edit/{employeeId}", [ctr\API\People\Employee\EmployeeC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\People\Employee\EmployeeC::class, 'store'])->name("store");
Route::post("/update/{employeeId}", [ctr\API\People\Employee\EmployeeC::class, 'update'])->name("update");
Route::post("/delete/{employeeId}", [ctr\API\People\Employee\EmployeeC::class, 'delete'])->name("delete");