<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\OutCommodity\OutCommodityAppsC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\OutCommodity\OutCommodityAppsC::class, 'invoice'])->name("invoice");
Route::post("/update/{outComId}", [ctr\API\Transactions\OutCommodity\OutCommodityAppsC::class, 'update'])->name("update");