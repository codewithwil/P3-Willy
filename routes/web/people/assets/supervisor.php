<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\People\Supervisor\SupervisorC::class, 'index'])->name("index");
Route::get("/create", [ctr\API\People\Supervisor\SupervisorC::class, 'create'])->name("create");
Route::get("/invoice", [ctr\API\People\Supervisor\SupervisorC::class, 'invoice'])->name("invoice");
Route::get("/edit/{supervisorId}", [ctr\API\People\Supervisor\SupervisorC::class, 'edit'])->name("edit");
Route::post("/store", [ctr\API\People\Supervisor\SupervisorC::class, 'store'])->name("store");
Route::post("/update/{supervisorId}", [ctr\API\People\Supervisor\SupervisorC::class, 'update'])->name("update");
Route::post("/delete/{supervisorId}", [ctr\API\People\Supervisor\SupervisorC::class, 'delete'])->name("delete");