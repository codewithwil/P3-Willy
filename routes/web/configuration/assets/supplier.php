<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Supplier\SupplierC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Supplier\SupplierC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Supplier\SupplierC::class, 'invoice'])->name("invoice");
Route::get("/edit/{supplierId}", [ctr\API\Resources\Supplier\SupplierC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Supplier\SupplierC::class, 'store'])->name("store");
Route::post("/update/{supplierId}", [ctr\API\Resources\Supplier\SupplierC::class, 'update'])->name("update");
Route::post("/delete/{supplierId}", [ctr\API\Resources\Supplier\SupplierC::class, 'delete'])->name("delete");