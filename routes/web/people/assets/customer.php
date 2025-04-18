<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\Customer\CustomerC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\People\Customer\CustomerC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\People\Customer\CustomerC::class, 'invoice'])->name("invoice");
Route::get("/edit/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\People\Customer\CustomerC::class, 'store'])->name("store");
Route::post("/update/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'update'])->name("update");
Route::post("/profileUpdate/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'profileUpdate'])->name("profileUpdate");
Route::post("/delete/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'delete'])->name("delete");  