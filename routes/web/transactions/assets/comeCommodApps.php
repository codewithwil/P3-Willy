<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\ComeCommodity\ComeCommodityAppsC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\ComeCommodity\ComeCommodityAppsC::class, 'invoice'])->name("invoice");
Route::post("/update/{comeComdId}", [ctr\API\Transactions\ComeCommodity\ComeCommodityAppsC::class, 'update'])->name("update");