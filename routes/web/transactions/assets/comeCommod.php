<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'create'])->name("create");
Route::get("/edit/{outComId}", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'edit'])->name("edit");
Route::get("/details/{comeComdId}", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'show'])->name("details");
Route::post("/store", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'store'])->name("store");
Route::post("/update/{comeComdId}", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'update'])->name("update");
Route::post("/delete/{comeComdId}", [ctr\API\Transactions\ComeCommodity\ComeCommodityC::class, 'delete'])->name("delete");