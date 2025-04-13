<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Attendance\Presences\PresencesC::class, 'index'])->name("index");
Route::get("/attendance", [ctr\API\Attendance\Presences\PresencesC::class, 'attendance'])->name("attendance");
Route::get("/edit/{presenceId}", [ctr\API\Attendance\Presences\PresencesC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\Attendance\Presences\PresencesC::class, 'store'])->name("store");
Route::post("/update/{presenceId}", [ctr\API\Attendance\Presences\PresencesC::class, 'update'])->name("update");