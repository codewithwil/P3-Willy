<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Branch\BranchC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Branch\BranchC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Branch\BranchC::class, 'invoice'])->name("invoice");
Route::get("/edit/{branchId}", [ctr\API\Resources\Branch\BranchC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Branch\BranchC::class, 'store'])->name("store");
Route::post("/update/{branchId}", [ctr\API\Resources\Branch\BranchC::class, 'update'])->name("update");
Route::post("/delete/{branchId}", [ctr\API\Resources\Branch\BranchC::class, 'delete'])->name("delete");