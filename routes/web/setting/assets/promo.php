<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Promo\PromoC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Promo\PromoC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Promo\PromoC::class, 'invoice'])->name("invoice");
Route::get("/edit/{promoId}", [ctr\API\Resources\Promo\PromoC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Promo\PromoC::class, 'store'])->name("store");
Route::post("/update/{promoId}", [ctr\API\Resources\Promo\PromoC::class, 'update'])->name("update");
Route::post("/delete/{promoId}", [ctr\API\Resources\Promo\PromoC::class, 'delete'])->name("delete");