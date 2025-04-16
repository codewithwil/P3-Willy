<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Member\MemberC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Resources\Member\MemberC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\Resources\Member\MemberC::class, 'invoice'])->name("invoice");
Route::get("/edit/{memberId}", [ctr\API\Resources\Member\MemberC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Member\MemberC::class, 'store'])->name("store");
Route::get("/success", [ctr\API\Resources\Member\MemberC::class, 'success'])->name("payment.success");
Route::post("/midtrans/notification", [ctr\API\Resources\Member\MemberC::class, 'notificationHandler'])->name("midtrans.notification");
Route::post("/update/{memberId}", [ctr\API\Resources\Member\MemberC::class, 'update'])->name("update");
Route::post("/delete/{memberId}", [ctr\API\Resources\Member\MemberC::class, 'delete'])->name("delete");