<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Commodity\CommodityC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\Commodity\CommodityC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Transactions\Commodity\CommodityC::class, 'create'])->name("create");
Route::get("/edit/{commoditiesId}", [ctr\API\Transactions\Commodity\CommodityC::class, 'edit'])->name("edit");
Route::get("/details/{commoditiesId}", [ctr\API\Transactions\Commodity\CommodityC::class, 'show'])->name("details");
Route::post("/store", [ctr\API\Transactions\Commodity\CommodityC::class, 'store'])->name("store");
Route::post("/update/{commoditiesId}", [ctr\API\Transactions\Commodity\CommodityC::class, 'update'])->name("update");
Route::post("/delete/{commoditiesId}", [ctr\API\Transactions\Commodity\CommodityC::class, 'delete'])->name("delete");