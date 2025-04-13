<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Service\ServiceAppsC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\Service\ServiceAppsC::class, 'invoice'])->name("invoice");
Route::post("/update/{serviceId}", [ctr\API\Transactions\Service\ServiceAppsC::class, 'update'])->name("update");