<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'invoice'])->name("invoice");
Route::get("/edit/{shiftId}", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'store'])->name("store");
Route::post("/update/{shiftId}", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'update'])->name("update");
Route::post("/delete/{shiftId}", [ctr\API\Resources\ManagementShift\Shift\ShiftC::class, 'delete'])->name("delete");