<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'create'])->name("create");
Route::get("/edit/{outComId}", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'edit'])->name("edit");
Route::get("/details/{outComId}", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'show'])->name("details");
Route::post("/store", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'store'])->name("store");
Route::post("/update/{outComId}", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'update'])->name("update");
Route::post("/delete/{outComId}", [ctr\API\Transactions\OutCommodity\OutCommodityC::class, 'delete'])->name("delete");