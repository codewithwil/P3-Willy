<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Loaning\LoaningAppsC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\Loaning\LoaningAppsC::class, 'invoice'])->name("invoice");
Route::post("/update/{loaningId}", [ctr\API\Transactions\Loaning\LoaningAppsC::class, 'update'])->name("update");