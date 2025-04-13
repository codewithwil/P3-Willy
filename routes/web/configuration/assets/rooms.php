<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Building\RoomC::class, 'index'])->name("index");
Route::get("/invoice", [ctr\API\Resources\Building\RoomC::class, 'invoice'])->name("invoice");
Route::get("/create", [ctr\API\Resources\Building\RoomC::class, 'create'])->name("create");
Route::get("/edit/{roomId}", [ctr\API\Resources\Building\RoomC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Resources\Building\RoomC::class, 'store'])->name("store");
Route::post("/update/{roomId}", [ctr\API\Resources\Building\RoomC::class, 'update'])->name("update");
Route::post("/delete/{roomId}", [ctr\API\Resources\Building\RoomC::class, 'delete'])->name("delete");