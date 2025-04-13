<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Transactions\Loaning\LoaningC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Transactions\Loaning\LoaningC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Transactions\Loaning\LoaningC::class, 'create'])->name("create");
Route::get("/edit/{loaningId}", [ctr\API\Transactions\Loaning\LoaningC::class, 'edit'])->name("edit");
Route::get("/details/{loaningId}", [ctr\API\Transactions\Loaning\LoaningC::class, 'show'])->name("details");
Route::post("/store", [ctr\API\Transactions\Loaning\LoaningC::class, 'store'])->name("store");
Route::post("/update/{loaningId}", [ctr\API\Transactions\Loaning\LoaningC::class, 'update'])->name("update");
Route::post("/return/{loaningId}", [ctr\API\Transactions\Loaning\LoaningC::class, 'return'])->name("return");
Route::post("/delete/{loaningId}", [ctr\API\Transactions\Loaning\LoaningC::class, 'delete'])->name("delete");