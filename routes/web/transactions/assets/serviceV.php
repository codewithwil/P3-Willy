<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'create'])->name("create");
Route::get("/edit/{serVId}", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'edit'])->name("edit");
Route::get("/details/{serVId}", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'show'])->name("details");
Route::post("/store", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'store'])->name("store");
Route::post("/update/{serVId}", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'update'])->name("update");
Route::post("/delete/{serVId}", [ctr\API\Transactions\Service\ServiceVehicleC::class, 'delete'])->name("delete");