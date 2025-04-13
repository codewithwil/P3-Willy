<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Report\Stock\StockReportC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Report\Stock\StockReportC::class, 'invoice'])->name("invoice");
Route::get("/details/{id}", [ctr\API\Report\Stock\StockReportC::class, 'details'])->name("details");