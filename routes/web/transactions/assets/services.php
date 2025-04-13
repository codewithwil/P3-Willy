<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Service\ServiceC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\Service\ServiceC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Transactions\Service\ServiceC::class, 'create'])->name("create");
Route::get("/edit/{serviceId}", [ctr\API\Transactions\Service\ServiceC::class, 'edit'])->name("edit");
Route::get("/details/{serviceId}", [ctr\API\Transactions\Service\ServiceC::class, 'show'])->name("details");
Route::post("/store", [ctr\API\Transactions\Service\ServiceC::class, 'store'])->name("store");
Route::post("/update/{serviceId}", [ctr\API\Transactions\Service\ServiceC::class, 'update'])->name("update");
Route::post("/return/{serviceId}", [ctr\API\Transactions\Service\ServiceC::class, 'return'])->name("return");
Route::post("/delete/{serviceId}", [ctr\API\Transactions\Service\ServiceC::class, 'delete'])->name("delete");