<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'invoice'])->name("invoice");
Route::get("/{customerId}", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'byId'])->name("byId");
Route::get("/edit/{serviceTransId}", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'edit'])->name("edit");
Route::get("/details/{serviceTransId}", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'details'])->name("details");
Route::post("/update/{serviceTransId}", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'update'])->name("update");
Route::post("/cash", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'cash'])->name("cash");
Route::post("/saldo", [ctr\API\Transactions\ServiceTransac\ServiceTransacC::class, 'saldo'])->name("saldo");