<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\User\UserC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\People\User\UserC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\People\User\UserC::class, 'invoice'])->name("invoice");
Route::get("/edit/{id}", [ctr\API\People\User\UserC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\People\User\UserC::class, 'store'])->name("store");
Route::post("/update/{id}", [ctr\API\People\User\UserC::class, 'update'])->name("update");
Route::post("/delete/{id}", [ctr\API\People\User\UserC::class, 'delete'])->name("delete");