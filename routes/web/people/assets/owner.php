<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\Owner\OwnerC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\People\Owner\OwnerC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\People\Owner\OwnerC::class, 'invoice'])->name("invoice");
Route::get("/edit/{ownerId}", [ctr\API\People\Owner\OwnerC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\People\Owner\OwnerC::class, 'store'])->name("store");
Route::post("/update/{ownerId}", [ctr\API\People\Owner\OwnerC::class, 'update'])->name("update");
Route::post("/delete/{ownerId}", [ctr\API\People\Owner\OwnerC::class, 'delete'])->name("delete");