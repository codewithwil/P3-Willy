<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Unit\UnitC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Unit\UnitC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Unit\UnitC::class, 'invoice'])->name("invoice");
Route::get("/edit/{unitId}", [ctr\API\Resources\Unit\UnitC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Unit\UnitC::class, 'store'])->name("store");
Route::post("/update/{unitId}", [ctr\API\Resources\Unit\UnitC::class, 'update'])->name("update");
Route::post("/delete/{unitId}", [ctr\API\Resources\Unit\UnitC::class, 'delete'])->name("delete");