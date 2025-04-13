<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Company\CompanyC::class, 'index'])->name("index");
Route::post("/store", [ctr\API\Resources\Company\CompanyC::class, 'store'])->name("store");
