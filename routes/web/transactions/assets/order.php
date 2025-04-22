<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'index'])->name("index");
Route::get("/{customerId}", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'byId'])->name("byId");
Route::post("/cash", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'cash'])->name("cash");
Route::post("/saldo", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'saldo'])->name("saldo");