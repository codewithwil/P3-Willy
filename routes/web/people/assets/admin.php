<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\Admin\AdminC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\People\Admin\AdminC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\People\Admin\AdminC::class, 'invoice'])->name("invoice");
Route::get("/edit/{adminId}", [ctr\API\People\Admin\AdminC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\People\Admin\AdminC::class, 'store'])->name("store");
Route::post("/update/{adminId}", [ctr\API\People\Admin\AdminC::class, 'update'])->name("update");
Route::post("/delete/{adminId}", [ctr\API\People\Admin\AdminC::class, 'delete'])->name("delete");