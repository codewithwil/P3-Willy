<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Building\BuildingC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Building\BuildingC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Building\BuildingC::class, 'invoice'])->name("invoice");
Route::get("/edit/{buildingId}", [ctr\API\Resources\Building\BuildingC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Building\BuildingC::class, 'store'])->name("store");
Route::post("/update/{buildingId}", [ctr\API\Resources\Building\BuildingC::class, 'update'])->name("update");
Route::post("/delete/{buildingId}", [ctr\API\Resources\Building\BuildingC::class, 'delete'])->name("delete");