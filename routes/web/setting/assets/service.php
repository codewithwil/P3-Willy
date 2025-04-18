<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Service\ServiceC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Service\ServiceC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Service\ServiceC::class, 'invoice'])->name("invoice");
Route::get("/edit/{serviceId}", [ctr\API\Resources\Service\ServiceC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Service\ServiceC::class, 'store'])->name("store");
Route::post("/update/{serviceId}", [ctr\API\Resources\Service\ServiceC::class, 'update'])->name("update");
Route::post("/delete/{serviceId}", [ctr\API\Resources\Service\ServiceC::class, 'delete'])->name("delete");