<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Merk\BrandMotorC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Merk\BrandMotorC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Merk\BrandMotorC::class, 'invoice'])->name("invoice");
Route::get("/edit/{brandMotorId}", [ctr\API\Resources\Merk\BrandMotorC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Merk\BrandMotorC::class, 'store'])->name("store");
Route::post("/update/{brandMotorId}", [ctr\API\Resources\Merk\BrandMotorC::class, 'update'])->name("update");
Route::post("/delete/{brandMotorId}", [ctr\API\Resources\Merk\BrandMotorC::class, 'delete'])->name("delete");