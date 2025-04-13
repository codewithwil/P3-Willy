<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Attendance\Location\LocationC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\Attendance\Location\LocationC::class, 'create'])->name("create");
Route::post("/store", [ctr\API\Attendance\Location\LocationC::class, 'store'])->name("store");