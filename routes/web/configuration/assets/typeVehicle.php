<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'invoice'])->name("invoice");
Route::get("/edit/{TypeVId}", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'store'])->name("store");
Route::post("/update/{TypeVId}", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'update'])->name("update");
Route::post("/delete/{TypeVId}", [ctr\API\Resources\TypeVehicle\TypeVehicleC::class, 'delete'])->name("delete");